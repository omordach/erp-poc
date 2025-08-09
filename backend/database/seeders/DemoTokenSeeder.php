<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Multitenancy\Models\Tenant;

class DemoTokenSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Tenant|null $tenant */
        $tenant = Tenant::current();
        if (! $tenant) {
            $this->command?->warn('No current tenant; run this inside tenant context.');

            return;
        }

        // Admin token
        $admin = User::on('tenant')->where('email', 'admin@example.test')->first();
        if ($admin) {
            $tokenResult = $admin->createToken('admin-demo', ['*']);
            $pat = $tokenResult->accessToken;
            $pat->tenant_id = (string) $tenant->id;
            $pat->save();
            $this->command?->info("Admin token: {$tokenResult->plainTextToken}");
        }

        // Viewer token
        $viewer = User::on('tenant')->where('email', 'member@example.test')->first();
        if ($viewer) {
            $tokenResult = $viewer->createToken('viewer-demo', ['*']);
            $pat = $tokenResult->accessToken;
            $pat->tenant_id = (string) $tenant->id;
            $pat->save();
            $this->command?->info("Viewer token: {$tokenResult->plainTextToken}");
        }
    }
}
