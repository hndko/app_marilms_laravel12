<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Membuat tabel tenant_users di database central.
 * Menggantikan tabel 'users' yang sebelumnya ada di database tenant terpisah.
 * Kolom tenant_id digunakan untuk isolasi data antar tenant.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_users', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('phone')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Email unik hanya dalam satu tenant (beda tenant boleh email sama)
            $table->unique(['tenant_id', 'email']);
            $table->index('tenant_id');
            $table->index('status');

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
        });

        Schema::create('tenant_password_reset_tokens', function (Blueprint $table) {
            $table->string('email');
            $table->string('tenant_id');
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            $table->primary(['email', 'tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_password_reset_tokens');
        Schema::dropIfExists('tenant_users');
    }
};
