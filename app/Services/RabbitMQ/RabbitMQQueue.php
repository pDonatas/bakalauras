<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ;

use App\Services\RabbitMQ\Jobs\RabbitMQJob;
use App\Services\RabbitMQ\Packer\PayloadPacker;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\InvalidPayloadException;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class RabbitMQQueue extends Queue implements QueueContract
{
    /**
     * Used for retry logic, to set the retries on the message metadata instead of the message body.
     */
    const ATTEMPT_COUNT_HEADERS_KEY = 'attempts_count';

    protected AMQPChannel $channel;

    protected mixed $declareExchange;

    /**
     * @var string[]
     */
    protected array $declaredExchanges = [];

    protected mixed $declareBindQueue;

    protected mixed $sleepOnError;

    /**
     * @var string[]
     */
    protected array $declaredQueues = [];

    protected mixed $defaultQueue;

    protected mixed $configQueue;

    protected mixed $configExchange;

    private ?int $retryAfter = null;

    private ?string $correlationId = null;

    /**
     * @param array<string, mixed> $config
     * @param Container            $container
     */
    public function __construct(
        protected AMQPStreamConnection $connection,
        protected $container,
        array $config
    ) {
        $this->defaultQueue = $config['queue'];
        $this->configQueue = $config['queue_params'];
        $this->configExchange = $config['exchange_params'];
        $this->declareExchange = $config['exchange_declare'];
        $this->declareBindQueue = $config['queue_declare_bind'];
        $this->sleepOnError = $config['sleep_on_error'] ?? 5;

        $this->channel = $this->getChannel();
    }

    /**
     * Get the size of the queue.
     *
     * @param string $queue
     */
    public function size($queue = null): int
    {
        list(, $messageCount) = $this->channel->queue_declare($this->getQueueName($queue), true);

        return $messageCount;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param string|null $job
     * @param mixed       $data
     * @param string|null $queue
     *
     * @throws \Exception
     */
    public function push($job, $data = '', $queue = null): bool|string
    {
        return $this->pushRaw($this->createPayload($job, $data, $queue), $queue, []);
    }

    /**
     * Create a payload string from the given job and data.
     *
     * @param string $job
     * @param string $queue
     *
     * @throws InvalidPayloadException
     */
    protected function createPayload($job, $queue, mixed $data = ''): string
    {
        return $this->getPayloadPacker()->pack($job, $this->getQueueName($queue), $data);
    }

    protected function getPayloadPacker(): PayloadPacker
    {
        return $this->container->make(PayloadPacker::class);
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param string               $payload
     * @param string               $queue
     * @param array<string, mixed> $options
     *
     * @throws \Exception
     */
    public function pushRaw($payload, $queue = null, array $options = []): string
    {
        try {
            $queue = $this->getQueueName($queue);
            if (isset($options['delay']) && $options['delay'] > 0) {
                list($queue, $exchange) = $this->declareDelayedQueue($queue, $options['delay']);
            } else {
                list($queue, $exchange) = $this->declareQueue($queue);
            }

            $headers = [
                'Content-Type'  => 'application/json',
                'delivery_mode' => 2,
            ];

            if (null !== $this->retryAfter) {
                $headers['application_headers'] = [self::ATTEMPT_COUNT_HEADERS_KEY => ['I', $this->retryAfter]];
            }

            // push job to a queue
            $message = new AMQPMessage($payload, $headers);

            $correlationId = $this->getCorrelationId();
            $message->set('correlation_id', $correlationId);

            // push task to a queue
            $this->channel->basic_publish($message, $exchange, $queue);

            return $correlationId;
        } catch (\ErrorException $exception) {
            $this->reportConnectionError('pushRaw', $exception);
        }

        return '';
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param \DateTime|int $delay
     * @param string        $job
     * @param mixed         $data
     * @param string        $queue
     *
     * @throws \Exception
     */
    public function later($delay, $job, $data = '', $queue = null): string
    {
        return $this->pushRaw($this->createPayload($job, $data), $queue, ['delay' => $this->secondsUntil($delay)]);
    }

    /**
     * Pop the next job off of the queue.
     *
     * @throws \Exception
     */
    public function pop(mixed $queue = null): ?RabbitMQJob
    {
        $queue = $this->getQueueName($queue);

        try {
            // declare queue if not exists
            $this->declareQueue($queue);

            // get envelope
            $message = $this->channel->basic_get($queue);

            if ($message instanceof AMQPMessage) {
                return new RabbitMQJob(
                    $this->container,
                    $this,
                    $this->channel,
                    $queue,
                    $message,
                    $this->connectionName
                );
            }
        } catch (\ErrorException $exception) {
            $this->reportConnectionError('pop', $exception);
        }

        return null;
    }

    /**
     * @param string $queue
     */
    private function getQueueName(?string $queue): string
    {
        return $queue ?: $this->defaultQueue;
    }

    private function getChannel(): AMQPChannel
    {
        return $this->connection->channel();
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function declareQueue(string $name): array
    {
        $name = $this->getQueueName($name);
        $exchange = $this->configExchange['name'] ?: $name;

        if ($this->declareExchange && ! in_array($exchange, $this->declaredExchanges, true)) {
            // declare exchange
            $this->channel->exchange_declare(
                $exchange,
                $this->configExchange['type'],
                $this->configExchange['passive'],
                $this->configExchange['durable'],
                $this->configExchange['auto_delete']
            );

            $this->declaredExchanges[] = $exchange;
        }

        if ($this->declareBindQueue && ! in_array($name, $this->declaredQueues, true)) {
            // declare queue
            $this->channel->queue_declare(
                $name,
                $this->configQueue['passive'],
                $this->configQueue['durable'],
                $this->configQueue['exclusive'],
                $this->configQueue['auto_delete']
            );

            // bind queue to the exchange
            $this->channel->queue_bind($name, $exchange, $name);

            $this->declaredQueues[] = $name;
        }

        return [$name, $exchange];
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function declareDelayedQueue(string $destination, \DateTime|int $delay): array
    {
        $delay = $this->secondsUntil($delay);
        $destination = $this->getQueueName($destination);
        $destinationExchange = $this->configExchange['name'] ?: $destination;
        $name = $this->getQueueName($destination) . '_deferred_' . $delay;
        $exchange = $this->configExchange['name'] ?: $destination;

        // declare exchange
        if (! in_array($exchange, $this->declaredExchanges, true)) {
            $this->channel->exchange_declare(
                $exchange,
                $this->configExchange['type'],
                $this->configExchange['passive'],
                $this->configExchange['durable'],
                $this->configExchange['auto_delete']
            );
        }

        // declare queue
        if (! in_array($name, $this->declaredQueues, true)) {
            $this->channel->queue_declare(
                $name,
                $this->configQueue['passive'],
                $this->configQueue['durable'],
                $this->configQueue['exclusive'],
                $this->configQueue['auto_delete'],
                false,
                new AMQPTable([
                    'x-dead-letter-exchange'    => $destinationExchange,
                    'x-dead-letter-routing-key' => $destination,
                    'x-message-ttl'             => $delay * 1000,
                ])
            );
        }

        // bind queue to the exchange
        $this->channel->queue_bind($name, $exchange, $name);

        return [$name, $exchange];
    }

    /**
     * Sets the attempts member variable to be used in message generation.
     */
    public function setAttempts(int $count): void
    {
        $this->retryAfter = $count;
    }

    /**
     * Sets the correlation id for a message to be published.
     */
    public function setCorrelationId(string $id): void
    {
        $this->correlationId = $id;
    }

    /**
     * Retrieves the correlation id, or a unique id.
     */
    public function getCorrelationId(): string
    {
        return $this->correlationId ?: uniqid('', true);
    }

    /**
     * @throws \Exception
     */
    protected function reportConnectionError(string $action, \Exception $e): void
    {
        Log::error('AMQP error while attempting ' . $action . ': ' . $e->getMessage());

        // If it's set to false, throw an error rather than waiting
        if (false === $this->sleepOnError) {
            throw new \RuntimeException('Error writing data to the connection with RabbitMQ');
        }

        // Sleep so that we don't flood the log file
        sleep($this->sleepOnError);
    }
}
