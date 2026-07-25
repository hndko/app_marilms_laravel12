<?php

namespace Database\Seeders;

use App\Models\Central\Owner;
use App\Models\Central\OwnerTokenBalance;
use App\Models\Central\Tenant;
use App\Models\Central\TokenTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Owner::where('email', 'owner@example.com')->exists() || Owner::where('slug', 'academy')->exists()) {
            return;
        }

        $owner = Owner::create([
            'name' => 'Demo Owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
            'organization_name' => 'MariLMS Academy',
            'slug' => 'academy',
            'status' => 'active',
            'type' => 'regular',
        ]);

        $tenant = Tenant::create([
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
}
