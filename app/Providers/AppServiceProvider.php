<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Payments\PaymentServiceInterface;
use App\Services\Payments\PayseraService;
use App\Services\RabbitMQ\Connector\RabbitMQConnector;
use Illuminate\Container\Container;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->app->bind(
            PaymentServiceInterface::class,
            PayseraService::class
        );

        $queue = $this->app['queue'];
        /** @var Container $container */
        $container = $this->app;
        $connector = new RabbitMQConnector($container);

        $queue->stopping(function () use ($connector) {
            $connector->connection()->close();
        });

        $queue->addConnector('rabbitmq', function () use ($connector) {
            return $connector;
        });

        $this->app->singleton(Client::class, function () {
            return new Client(
                config('sms.sid'),
                config('sms.token')
            );
        });
    }
}
