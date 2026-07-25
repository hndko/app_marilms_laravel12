<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SuperAdminSeeder::class,
            SystemSettingSeeder::class,
            TokenPackageSeeder::class,
            LlmProviderSeeder::class,
            DemoOwnerSeeder::class,
        ]);
    }
}
