<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;

class TenantTokenScope
{
    public function handle(Request $request, Closure $next)
    {
        $currentTenantId = Tenant::current()?->id;
        $tokenTenantId = auth()->user()?->currentAccessToken()?->tenant_id;

        if ($currentTenantId !== $tokenTenantId) {
            return response()->json([
                'error' => [
                    'status' => 403,
                    'code' => 'TENANT_TOKEN_MISMATCH',
                    'message' => 'Tenant token mismatch.',
                ],
            ], 403);
        }

        return $next($request);
    }
}
