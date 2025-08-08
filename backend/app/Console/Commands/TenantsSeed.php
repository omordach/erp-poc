<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Multitenancy\Models\Tenant;

class TenantsSeed extends Command
{
    protected $signature = 'tenants:seed:poc {--tenant=*}';
    protected $description = 'Seed tenant databases with demo data';

    public function handle(): int
    {
        $keys = $this->option('tenant');
        $query = Tenant::query();
        if (!empty($keys)) {
            $query->whereIn('key', $keys);
        }
        $tenants = $query->get();

        foreach ($tenants as $tenant) {
            $this->info("Seeding tenant {$tenant->key}");
            $tenant->makeCurrent();

            $this->call('db:seed', ['--class' => \Database\Seeders\TenantSeeder::class, '--force' => true]);
            $this->call('db:seed', ['--class' => \Database\Seeders\DemoTokenSeeder::class, '--force' => true]);

            $tenant->forget();
        }

        return self::SUCCESS;
    }
}
