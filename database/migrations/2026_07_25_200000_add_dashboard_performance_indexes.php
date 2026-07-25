<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Performance Indexes for quiz_attempts dashboard aggregations & filters
        if (Schema::hasTable('quiz_attempts')) {
            try {
                Schema::table('quiz_attempts', function (Blueprint $table) {
                    $table->index(['tenant_id', 'created_at'], 'idx_qa_tenant_created');
                });
            } catch (\Throwable $e) {}

            try {
                Schema::table('quiz_attempts', function (Blueprint $table) {
                    $table->index(['tenant_id', 'status', 'created_at'], 'idx_qa_tenant_status_created');
                });
            } catch (\Throwable $e) {}

            try {
                Schema::table('quiz_attempts', function (Blueprint $table) {
                    $table->index(['tenant_id', 'user_id', 'status'], 'idx_qa_tenant_user_status');
                });
            } catch (\Throwable $e) {}

            try {
                Schema::table('quiz_attempts', function (Blueprint $table) {
                    $table->index(['tenant_id', 'is_flagged'], 'idx_qa_tenant_flagged');
                });
            } catch (\Throwable $e) {}
        }

        // Performance Indexes for quizzes
        if (Schema::hasTable('quizzes')) {
            try {
                Schema::table('quizzes', function (Blueprint $table) {
                    $table->index(['tenant_id', 'status', 'created_at'], 'idx_quizzes_tenant_status_created');
                });
            } catch (\Throwable $e) {}
        }

        // Performance Indexes for token_orders
        if (Schema::hasTable('token_orders')) {
            try {
                Schema::table('token_orders', function (Blueprint $table) {
                    $table->index(['status', 'created_at'], 'idx_orders_status_created');
                });
            } catch (\Throwable $e) {}

            try {
                Schema::table('token_orders', function (Blueprint $table) {
                    $table->index(['status', 'paid_at'], 'idx_orders_status_paid');
                });
            } catch (\Throwable $e) {}
        }

        // Performance Indexes for tenant_users
        if (Schema::hasTable('tenant_users')) {
            try {
                Schema::table('tenant_users', function (Blueprint $table) {
                    $table->index(['tenant_id', 'created_at'], 'idx_tu_tenant_created');
                });
            } catch (\Throwable $e) {}
        }

        // Performance Indexes for owners
        if (Schema::hasTable('owners')) {
            try {
                Schema::table('owners', function (Blueprint $table) {
                    $table->index('created_at', 'idx_owners_created');
                });
            } catch (\Throwable $e) {}
        }
    }

    public function down(): void
    {
        // Safe down
    }
};
