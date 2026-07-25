<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Mengubah tipe kolom user_id pada tabel sessions dari BIGINT UNSIGNED menjadi VARCHAR(255).
 * Hal ini diperlukan karena model Owner menggunakan UUID (string 36 karakter) sebagai primary key.
 * Tanpa perubahan ini, MySQL akan memberikan error 1265 Data truncated saat Owner login.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sessions')) {
            DB::statement("ALTER TABLE `sessions` MODIFY `user_id` VARCHAR(255) NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sessions')) {
            DB::statement("ALTER TABLE `sessions` MODIFY `user_id` BIGINT UNSIGNED NULL");
        }
    }
};
