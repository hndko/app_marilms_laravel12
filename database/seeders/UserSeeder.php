<?php

namespace Database\Seeders;

use App\Models\Central\Owner;
use App\Models\Central\OwnerTokenBalance;
use App\Models\Central\Tenant;
use App\Models\Central\TokenTransaction;
use App\Models\Tenant\User as ParticipantUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds for SuperAdmin, Owner, and Participant.
     */
    public function run(): void
    {
        // -------------------------------------------------------
        // 1. SuperAdmin User (Guard: web)
        // -------------------------------------------------------
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (method_exists($superAdmin, 'assignRole')) {
            $superAdmin->assignRole('superadmin');
        }

        // -------------------------------------------------------
        // 2. Demo Owner User & Tenant (Guard: owner)
        // -------------------------------------------------------
        $owner = Owner::where('email', 'owner@example.com')->first();

        if (!$owner && !Owner::where('slug', 'academy')->exists()) {
            $owner = Owner::create([
                'name' => 'Demo Owner',
                'email' => 'owner@example.com',
                'password' => Hash::make('password'),
                'organization_name' => 'MariLMS Academy',
                'slug' => 'academy',
                'status' => 'active',
                'type' => 'regular',
            ]);

            Tenant::create([
                'id' => 'academy',
                'slug' => 'academy',
                'name' => 'MariLMS Academy',
                'owner_id' => $owner->id,
                'is_active' => true,
            ]);

            OwnerTokenBalance::create([
                'owner_id' => $owner->id,
                'balance' => 150,
                'is_unlimited' => false,
            ]);

            TokenTransaction::create([
                'owner_id' => $owner->id,
                'type' => 'credit',
                'amount' => 150,
                'source' => 'register',
                'reference_id' => (string) $owner->id,
                'note' => 'Token awal seeder demo (150 token)',
                'created_at' => now(),
            ]);
        }

        // -------------------------------------------------------
        // 3. Demo Participant User (Guard: participant)
        // -------------------------------------------------------
        $tenantModel = Tenant::find('academy');
        if ($tenantModel) {
            tenancy()->initialize($tenantModel);

            if (!ParticipantUser::where('email', 'participant@example.com')->exists()) {
                ParticipantUser::create([
                    'tenant_id' => 'academy',
                    'name' => 'Budi Santoso (Peserta Demo)',
                    'email' => 'participant@example.com',
                    'phone' => '081234567890',
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
            }

            tenancy()->end();
        }
    }
}
