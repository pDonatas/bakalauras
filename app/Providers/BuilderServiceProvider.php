<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PHPageBuilder\PHPageBuilder;
use Schema;

class BuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if (Schema::hasTable(config('builder.storage.database.prefix') . 'settings')) {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/builder.php');

            $this->app->singleton('phpPageBuilder', function () {
                $config = config('builder') ?? [];
                if (! is_array($config)) {
                    $config = [];
                }

                return new PHPageBuilder($config);
            });
            $this->app->make('phpPageBuilder');
        }
    }
}
