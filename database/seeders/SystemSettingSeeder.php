<?php

namespace Database\Seeders;

use App\Models\Central\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
}
