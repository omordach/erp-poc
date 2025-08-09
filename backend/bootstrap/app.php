<?php

use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        Laravel\Sanctum\SanctumServiceProvider::class,
        Spatie\Multitenancy\MultitenancyServiceProvider::class,
        App\Providers\TenancyServiceProvider::class,
        App\Providers\ModulesServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function ($middleware) {
        //
    })
    ->withExceptions(function ($exceptions) {
        //
    })
    ->create();
