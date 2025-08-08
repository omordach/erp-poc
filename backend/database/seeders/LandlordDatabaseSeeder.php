<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandlordDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure tenants table exists
        if (! DB::connection('landlord')->getSchemaBuilder()->hasTable('tenants')) {
            $this->command->warn('Tenants table missing; run landlord migrations first.');
            return;
        }

        $tenants = [
            ['key' => 'opeiu33',  'name' => 'OPEIU Local 33',  'database' => 'tenant_opeiu33'],
            ['key' => 'iatse100', 'name' => 'IATSE Local 100', 'database' => 'tenant_iatse100'],
        ];

        foreach ($tenants as $t) {
            // Create tenant databases if not present
            DB::connection('landlord')->statement("CREATE DATABASE IF NOT EXISTS `{$t['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            $exists = DB::connection('landlord')->table('tenants')->where('key', $t['key'])->exists();
            if (! $exists) {
                DB::connection('landlord')->table('tenants')->insert([
                    'key' => $t['key'],
                    'name' => $t['name'],
                    'database' => $t['database'],
                    'data' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command?->info('Landlord seeded: tenants created and databases ensured.');
    }
}
