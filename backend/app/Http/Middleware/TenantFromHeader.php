<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TenantFromHeader
{
    public function handle(Request $request, Closure $next)
    {
        // No-op: TenantFinder reads header. Keep here for ordering & clarity.
        return $next($request);
    }
}
