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

        // Only enforce tenant scope when a token is present. This allows
        // unauthenticated requests (e.g. requesting a token) to pass through
        // without triggering a mismatch error.
        if ($tokenTenantId !== null && $currentTenantId !== $tokenTenantId) {
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
