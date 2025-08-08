<?php

use App\Tenancy\TenantFinder;
use Spatie\Multitenancy\Actions\MakeQueueTenantAwareAction;
use Spatie\Multitenancy\Models\Tenant;

return [

    'tenant_model' => Tenant::class,

    'tenant_finder' => TenantFinder::class,

    'switch_tenant_tasks' => [
        Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask::class,
        Spatie\Multitenancy\Tasks\SwitchTenantCacheTask::class,
    ],

    'switch_tenant_tasks_queue' => [
        MakeQueueTenantAwareAction::class,
    ],

    'landlord_database_connection_name' => env('LANDLORD_DB_CONNECTION', 'landlord'),

    'tenant_database_connection_name' => env('TENANT_DB_CONNECTION', 'tenant'),

    'initialize_tenancy_middleware' => [
        // We explicitly resolve via TenantFinder; plus custom header fallback middleware in Kernel.
    ],
];
