<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next, string $moduleKey, string $requiredRole = 'viewer')
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'error' => ['status' => 401, 'code' => 'UNAUTHENTICATED', 'message' => 'Unauthenticated.']
            ], 401);
        }

        $tenant = Tenant::current();
        if (!$tenant) {
            return response()->json([
                'error' => ['status' => 400, 'code' => 'NO_TENANT', 'message' => 'Tenant not resolved.']
            ], 400);
        }

        // PoC: user has roles in table user_roles with scope (union_id|null, local_id|null, module_key)
        // We'll check simplest form: user()->hasRole($moduleKey, $requiredRole)
        if (method_exists($user, 'hasRoleForModule') && $user->hasRoleForModule($moduleKey, $requiredRole)) {
            return $next($request);
        }

        return response()->json([
            'error' => ['status' => 403, 'code' => 'FORBIDDEN', 'message' => 'Insufficient permissions.']
        ], 403);
    }
}