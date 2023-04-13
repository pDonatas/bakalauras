<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\Connector;

use App\Services\RabbitMQ\RabbitMQQueue;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\Connectors\ConnectorInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConnector implements ConnectorInterface
{
    private AMQPStreamConnection $connection;

    /**
     * RabbitMQConnector constructor.
     */
    public function __construct(
        /**
         * @var Container
         */
        protected Container $container
    ) {}

    /**
     * Establish a queue connection.
     *
     * @param array<string, mixed> $config
     */
    public function connect(array $config): Queue
    {
        $this->connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password'],
            $config['vhost']
        );

        return new RabbitMQQueue(
            $this->connection,
            $this->container,
            $config
        );
    }

    public function connection(): AMQPStreamConnection
    {
        return $this->connection;
    }
}
