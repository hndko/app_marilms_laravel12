<?php

namespace Database\Seeders;

use Database\Seeders\LlmProviderSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SystemSettingSeeder;
use Database\Seeders\TokenPackageSeeder;
use Database\Seeders\UserSeeder;
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
            UserSeeder::class,
            SystemSettingSeeder::class,
            TokenPackageSeeder::class,
            LlmProviderSeeder::class,
        ]);
    }
}
