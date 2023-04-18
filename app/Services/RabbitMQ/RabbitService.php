<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitService
{
    protected AMQPChannel $channel;

    public function __construct(
        protected AMQPStreamConnection $connection
    ) {}

    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function channel(): AMQPChannel
    {
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare('queue', false, true, false, false);

        return $this->channel;
    }

    public function reconnect(): void
    {
        $this->connection->reconnect();
    }
}
