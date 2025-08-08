<?php

namespace App\Tenancy;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder as BaseTenantFinder;

class TenantFinder extends BaseTenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        // 1) Subdomain: {tenant}.example.local
        $host = $request->getHost(); // e.g., iatse100.example.local
        $parts = explode('.', $host);
        $candidate = count($parts) > 2 ? $parts[0] : null;

        // 2) Header fallback for local dev
        $headerTenant = $request->header('X-Tenant');

        $key = $headerTenant ?: $candidate;

        return $key
            ? Tenant::query()->where('key', $key)->first()
            : null;
    }
}
