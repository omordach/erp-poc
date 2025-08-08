<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\TenantsMigrate;
use App\Console\Commands\TenantsSeed;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TenantsMigrate::class,
                TenantsSeed::class,
            ]);
        }
    }
}
