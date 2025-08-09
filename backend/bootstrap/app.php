<?php

use App\Http\Middleware\CheckModulePermission;
use App\Http\Middleware\TenantFromHeader;
use App\Http\Middleware\TenantTokenScope;
use Fruitcake\Cors\HandleCors;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;

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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([HandlePrecognitiveRequests::class, HandleCors::class]);

        $middleware->api(prepend: [
            TenantFromHeader::class,
            TenantTokenScope::class,
        ]);

        $middleware->alias([
            'module.permission' => CheckModulePermission::class,
        ]);
    })
    ->withExceptions(function ($exceptions) {
        //
    })
    ->create();
