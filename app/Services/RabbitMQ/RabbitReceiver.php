<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitReceiver
{
    private AMQPChannel $channel;

    /**
     * @var array<int, string>
     */
    private array $messages;

    public function __construct(
        private readonly RabbitService $rabbit
    ) {
        $this->channel = $this->rabbit->channel();
        $this->messages = [];
    }

    /**
     * @return string[]
     *
     * @throws \ErrorException
     */
    public function receive(): array
    {
        $this->channel->basic_consume('queue', '', false, true, false, false, [$this, 'processMessage']);
        while ($this->channel->is_consuming()) {
            try {
                $this->channel->wait(null, false, 10);
            } catch (AMQPTimeoutException) {
                $this->channel->close();
                $this->rabbit->close();

                return $this->messages;
            } catch (AMQPConnectionClosedException $e) {
                $this->rabbit->reconnect();
            }
        }

        return $this->messages;
    }

    public function processMessage(AMQPMessage $message): void
    {
        $message = unserialize($message->getBody());

        $message->send();

        $this->messages[] = $message;
    }
}
