<?php

namespace Database\Seeders;

use App\Models\Central\LlmProvider;
use App\Models\Central\SystemSetting;
use App\Models\Central\TokenPackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $this->createRoles();

        // Create SuperAdmin
        $this->createSuperAdmin();

        // Seed system settings
        $this->seedSystemSettings();

        // Seed default token packages
        $this->seedTokenPackages();

        // Seed default LLM provider (OpenRouter)
        $this->seedDefaultLlmProvider();
    }

    private function createRoles(): void
    {
        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'owner']);
        Role::firstOrCreate(['name' => 'participant', 'guard_name' => 'participant']);
    }

    private function createSuperAdmin(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@marilms.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $superAdmin->assignRole('superadmin');
    }

    private function seedSystemSettings(): void
    {
        $settings = [
            ['key' => 'free_token_on_register', 'value' => '50', 'type' => 'integer', 'group' => 'token', 'description' => 'Token gratis saat Owner baru mendaftar'],
            ['key' => 'token_per_question', 'value' => '1', 'type' => 'integer', 'group' => 'token', 'description' => 'Token yang dikonsumsi per soal yang di-generate'],
            ['key' => 'default_seconds_per_question', 'value' => '60', 'type' => 'integer', 'group' => 'timer', 'description' => 'Detik per soal jika kuis tidak punya durasi custom'],
            ['key' => 'tab_switch_action', 'value' => 'end_quiz', 'type' => 'string', 'group' => 'timer', 'description' => 'Aksi saat peserta pindah tab: end_quiz'],
            ['key' => 'app_name', 'value' => 'MariLMS', 'type' => 'string', 'group' => 'general', 'description' => 'Nama Aplikasi'],
            ['key' => 'app_timezone', 'value' => 'Asia/Jakarta', 'type' => 'string', 'group' => 'general', 'description' => 'Timezone default'],
            ['key' => 'app_language', 'value' => 'id', 'type' => 'string', 'group' => 'general', 'description' => 'Bahasa default'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'general', 'description' => 'Mode maintenance'],
            ['key' => 'landing_page_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'general', 'description' => 'Tampilkan landing page'],
            ['key' => 'token_low_threshold', 'value' => '10', 'type' => 'integer', 'group' => 'token', 'description' => 'Threshold notifikasi token rendah'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    private function seedTokenPackages(): void
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

    private function seedDefaultLlmProvider(): void
    {
        LlmProvider::firstOrCreate(
            ['name' => 'OpenRouter (Default)'],
            [
                'provider_type' => 'openrouter',
                'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1'),
                'api_key' => env('OPENROUTER_API_KEY', ''),
                'model' => env('OPENROUTER_MODEL', 'openai/gpt-4o-mini'),
                'max_tokens' => 4000,
                'temperature' => 0.7,
                'priority' => 1,
                'status' => 'active',
            ]
        );
    }
}
