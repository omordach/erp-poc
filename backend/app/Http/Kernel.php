<?php

namespace App\Http;

use App\Http\Middleware\CheckModulePermission;
use App\Http\Middleware\TenantFromHeader;
use App\Http\Middleware\TenantTokenScope;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

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

    protected $middlewareAliases = [
        'module.permission' => CheckModulePermission::class,
    ];
}
