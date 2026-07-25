<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change source column in token_transactions from restrictive ENUM to VARCHAR(50)
        try {
            DB::statement("ALTER TABLE `token_transactions` MODIFY COLUMN `source` VARCHAR(50) NOT NULL");
        } catch (\Throwable $e) {
            // Fallback for engines/drivers if statement varies
            Schema::table('token_transactions', function (Blueprint $table) {
                $table->string('source', 50)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `token_transactions` MODIFY COLUMN `source` ENUM('register', 'purchase', 'manual_topup', 'quiz_generate', 'quiz_generation', 'package_purchase') NOT NULL");
        } catch (\Throwable $e) {
            // Silence down migration
        }
    }
};
