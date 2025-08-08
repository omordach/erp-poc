<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;
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
        $admin = User::where('email','admin@example.test')->first();
        if ($admin) {
            $token = $admin->createToken('admin-demo', ['*']);
            $pat = PersonalAccessToken::findToken($token->plainTextToken);
            if ($pat) {
                $pat->tenant_id = (string)$tenant->id;
                $pat->save();
            }
            $this->command?->info("Admin token: {$token->plainTextToken}");
        }

        // Viewer token
        $viewer = User::where('email','member@example.test')->first();
        if ($viewer) {
            $token = $viewer->createToken('viewer-demo', ['*']);
            $pat = PersonalAccessToken::findToken($token->plainTextToken);
            if ($pat) {
                $pat->tenant_id = (string)$tenant->id;
                $pat->save();
            }
            $this->command?->info("Viewer token: {$token->plainTextToken}");
        }
    }
}
