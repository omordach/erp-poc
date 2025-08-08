<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Multitenancy\Models\Tenant;

class TenantsMigrate extends Command
{
    protected $signature = 'tenants:migrate:poc {--tenant=* : tenant keys (optional)}';
    protected $description = 'Run tenant migrations from database/migrations_tenant for all or selected tenants';

    public function handle(): int
    {
        $keys = $this->option('tenant');
        $query = Tenant::query();
        if (!empty($keys)) {
            $query->whereIn('key', $keys);
        }

        $tenants = $query->get();

        foreach ($tenants as $tenant) {
            $this->info("Migrating tenant {$tenant->key} ({$tenant->database})");
            $tenant->makeCurrent();

            $this->call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations_tenant',
                '--force' => true,
            ]);

            $tenant->forget();
        }

        return self::SUCCESS;
    }
}
