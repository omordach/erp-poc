<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // Bail out if we've already seeded (admin user exists)
        if (DB::connection('tenant')->table('users')->where('email', 'admin@example.test')->exists()) {
            return;
        }

        // USERS
        $adminId = DB::connection('tenant')->table('users')->insertGetId([
            'name' => 'Staff Admin',
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $memberViewId = DB::connection('tenant')->table('users')->insertGetId([
            'name' => 'Member Viewer',
            'email' => 'member@example.test',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // MEMBERSHIP (Union/Local/Member)
        $unionId = DB::connection('tenant')->table('unions')->insertGetId([
            'name' => 'Demo Union',
            'code' => 'DU',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $localA = DB::connection('tenant')->table('locals')->insertGetId([
            'union_id' => $unionId, 'name' => 'Local A', 'number' => 'A-100',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $localB = DB::connection('tenant')->table('locals')->insertGetId([
            'union_id' => $unionId, 'name' => 'Local B', 'number' => 'B-200',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $m1 = DB::connection('tenant')->table('members')->insertGetId([
            'local_id' => $localA, 'first_name' => 'Alice', 'last_name' => 'Johnson',
            'email' => 'alice@example.test', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $m2 = DB::connection('tenant')->table('members')->insertGetId([
            'local_id' => $localB, 'first_name' => 'Bob', 'last_name' => 'Smith',
            'email' => 'bob@example.test', 'created_at' => now(), 'updated_at' => now(),
        ]);

        // GRIEVANCES
        DB::connection('tenant')->table('grievances')->insert([
            ['title'=>'Missing pay', 'description'=>'Late paycheck for July', 'status'=>'open', 'opened_at'=>now(), 'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Safety concern', 'description'=>'Faulty harnesses', 'status'=>'pending', 'opened_at'=>now(), 'created_at'=>now(),'updated_at'=>now()],
        ]);

        // EVENTS
        DB::connection('tenant')->table('events')->insert([
            ['title'=>'Monthly Meeting','starts_at'=>now()->addDays(3),'ends_at'=>now()->addDays(3)->addHours(2),'location'=>'Hall A','created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Training','starts_at'=>now()->addDays(7),'ends_at'=>now()->addDays(7)->addHours(4),'location'=>'Room 12','created_at'=>now(),'updated_at'=>now()],
        ]);

        // PAYMENTS (Invoices + Payments)
        $inv1 = DB::connection('tenant')->table('invoices')->insertGetId([
            'number' => 'INV-1001', 'amount' => 120.00, 'status' => 'issued', 'issued_at' => now(), 'due_at' => now()->addDays(30),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        DB::connection('tenant')->table('payments')->insert([
            'invoice_id' => $inv1, 'amount' => 120.00, 'paid_at' => now(), 'method' => 'card',
            'created_at'=>now(),'updated_at'=>now(),
        ]);

        // ROLES
        DB::connection('tenant')->table('user_roles')->insert([
            ['user_id'=>$adminId,'module_key'=>'membership','role'=>'admin','union_id'=>null,'local_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['user_id'=>$adminId,'module_key'=>'grievances','role'=>'admin','union_id'=>null,'local_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['user_id'=>$adminId,'module_key'=>'payments','role'=>'admin','union_id'=>null,'local_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['user_id'=>$adminId,'module_key'=>'events','role'=>'admin','union_id'=>null,'local_id'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['user_id'=>$memberViewId,'module_key'=>'membership','role'=>'viewer','union_id'=>$unionId,'local_id'=>$localA,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // Ensure Accounts created by event listener on MemberCreated:
        // In PoC seeding, we can simulate by inserting directly
        DB::connection('tenant')->table('accounts')->insert([
            ['member_id'=>$m1,'status'=>'active','opened_at'=>now(),'created_at'=>now(),'updated_at'=>now()],
            ['member_id'=>$m2,'status'=>'active','opened_at'=>now(),'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
