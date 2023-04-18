<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Message\AMQPMessage;

class RabbitSender
{
    public function __construct(
        private RabbitService $rabbit
    ) {}

    public function send(object $message): void
    {
        $channel = $this->rabbit->channel();
        $msg = new AMQPMessage(serialize($message));
        $channel->basic_publish($msg, '', 'queue');
        $channel->close();
    }

    public function close(): void
    {
        $this->rabbit->close();
    }
}
