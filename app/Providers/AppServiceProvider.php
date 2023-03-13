<?php

namespace App\Providers;

use App\Services\Payments\PaymentServiceInterface;
use App\Services\Payments\PayseraService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        $this->app->bind(
            PaymentServiceInterface::class,
            PayseraService::class
        );
    }
}
