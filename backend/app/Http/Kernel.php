<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\TenantFromHeader;
use App\Http\Middleware\TenantTokenScope;
use App\Http\Middleware\CheckModulePermission;

class Kernel extends HttpKernel
{
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        \Fruitcake\Cors\HandleCors::class,
    ];

    protected $middlewareGroups = [
        'api' => [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            TenantFromHeader::class,    // ensures TenantFinder sees header
            TenantTokenScope::class,    // ensures token matches tenant
        ],
    ];

    protected $routeMiddleware = [
        'module.permission' => CheckModulePermission::class,
    ];
}
