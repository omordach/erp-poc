<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\Config;

class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Connections are defined in .env and database config; we ensure names exist.
        Config::set('database.connections.landlord', [
            'driver' => 'mysql',
            'host' => env('LANDLORD_DB_HOST', '127.0.0.1'),
            'port' => env('LANDLORD_DB_PORT', '3306'),
            'database' => env('LANDLORD_DB_DATABASE', 'landlord'),
            'username' => env('LANDLORD_DB_USERNAME', 'root'),
            'password' => env('LANDLORD_DB_PASSWORD', ''),
            'unix_socket' => env('LANDLORD_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            // Will be overridden per-tenant by Spatie SwitchTenantDatabaseTask
            'host' => env('TENANT_DB_HOST', '127.0.0.1'),
            'port' => env('TENANT_DB_PORT', '3306'),
            'database' => 'tenant-placeholder',
            'username' => env('TENANT_DB_USERNAME', 'root'),
            'password' => env('TENANT_DB_PASSWORD', ''),
            'unix_socket' => env('TENANT_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        Tenant::morphMap([
            'tenant' => \Spatie\Multitenancy\Models\Tenant::class,
        ]);
    }

    public function boot(): void
    {
        //
    }
}
