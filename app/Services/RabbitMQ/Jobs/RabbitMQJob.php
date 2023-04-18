<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\Jobs;

use App\Services\RabbitMQ\Packer\PayloadPacker;
use App\Services\RabbitMQ\RabbitMQQueue;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\Jobs\JobName;
use Illuminate\Support\Str;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQJob extends Job implements JobContract
{
    /**
     * Same as RabbitMQQueue, used for attempt counts.
     */
    const ATTEMPT_COUNT_HEADERS_KEY = 'attempts_count';

    /**
     * @param Container $container
     * @param string    $queue
     * @param string    $connectionName
     */
    public function __construct(
        public $container,
        protected RabbitMQQueue $connection,
        protected AMQPChannel $channel,
        protected $queue,
        protected AMQPMessage $message,
        public $connectionName
    ) {}

    /**
     * Fire the job.
     *
     * @throws \Exception
     */
    public function fire(): void
    {
        try {
            $payload = $this->payload();

            list($class, $method) = JobName::parse($payload['job']);

            $this->instance = ($this->resolve($class))->{$method}($this, $payload['data']);
        } catch (\Exception $exception) {
            if (Str::contains($exception->getMessage(), ['detected deadlock'])) {
                sleep(2);
                $this->fire();

                return;
            }

            throw $exception;
        }
    }

    /**
     * Get the decoded body of the job.
     *
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return $this->getPayloadPacker()->unpack($this->getRawBody(), $this->getQueue());
    }

    protected function getPayloadPacker(): PayloadPacker
    {
        return $this->container->make(PayloadPacker::class);
    }

    /**
     * Get the number of times the job has been attempted.
     */
    public function attempts(): int
    {
        if (true === $this->message->has('application_headers')) {
            $headers = $this->message->get('application_headers')->getNativeData();

            if (true === isset($headers[self::ATTEMPT_COUNT_HEADERS_KEY])) {
                return $headers[self::ATTEMPT_COUNT_HEADERS_KEY];
            }
        }

        // set default job attempts to 1 so that jobs can run without retry
        return 1;
    }

    /**
     * Get the raw body string for the job.
     */
    public function getRawBody(): string
    {
        return $this->message->body;
    }

    /**
     * Delete the job from the queue.
     */
    public function delete(): void
    {
        parent::delete();
        $this->channel->basic_ack($this->message->get('delivery_tag'));
    }

    /**
     * Release the job back into the queue.
     *
     * @param int $delay
     *
     * @throws \Exception
     */
    public function release($delay = 0): void
    {
        parent::release($delay);

        $this->delete();
        $this->setAttempts($this->attempts() + 1);

        $body = $this->payload();

        /*
         * Some jobs don't have the command set, so fall back to just sending it the job name string
         */
        if (true === isset($body['data']['command'])) {
            $job = $this->unserialize($body);
        } else {
            $job = $this->getName();
        }

        $data = $body['data'];

        if ($delay > 0) {
            $this->connection->later($delay, $job, $data, $this->getQueue());
        } else {
            $this->connection->push($job, $data, $this->getQueue());
        }
    }

    /**
     * Sets the count of attempts at processing this job.
     */
    private function setAttempts(int $count): void
    {
        $this->connection->setAttempts($count);
    }

    /**
     * Get the job identifier.
     */
    public function getJobId(): string
    {
        return $this->message->get('correlation_id');
    }

    /**
     * Sets the job identifier.
     */
    public function setJobId(string $id): void
    {
        $this->connection->setCorrelationId($id);
    }

    /**
     * Unserialize job.
     *
     * @param array<string, mixed> $body
     *
     * @throws \Exception
     */
    private function unserialize(array $body): mixed
    {
        try {
            return unserialize($body['data']['command']);
        } catch (\Exception $exception) {
            if (
                Str::contains($exception->getMessage(), ['detected deadlock'])
            ) {
                sleep(2);

                return $this->unserialize($body);
            }

            throw $exception;
        }
    }
}
