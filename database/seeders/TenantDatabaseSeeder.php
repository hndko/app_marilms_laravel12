<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder for tenant databases.
 * This runs automatically when a new tenant is created.
 */
class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tenant-specific seeding can go here
        // For now, tenant databases start empty — participants & quizzes are created by the Owner
    }
}
