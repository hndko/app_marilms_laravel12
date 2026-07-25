<?php

namespace Database\Seeders;

use App\Models\Central\TokenPackage;
use Illuminate\Database\Seeder;

class TokenPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            ['name' => 'Starter', 'token_amount' => 100, 'price_idr' => 25000, 'description' => 'Paket pemula — 100 token untuk generate soal', 'sort_order' => 1],
            ['name' => 'Basic', 'token_amount' => 250, 'price_idr' => 50000, 'description' => 'Paket dasar — 250 token', 'sort_order' => 2],
            ['name' => 'Pro', 'token_amount' => 500, 'price_idr' => 90000, 'description' => 'Paket profesional — 500 token (hemat 10%)', 'sort_order' => 3],
            ['name' => 'Enterprise', 'token_amount' => 1000, 'price_idr' => 150000, 'description' => 'Paket enterprise — 1000 token (hemat 25%)', 'sort_order' => 4],
        ];

        foreach ($packages as $package) {
            TokenPackage::firstOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}
