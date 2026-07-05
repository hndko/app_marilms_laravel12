<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateway_configs', function (Blueprint $table) {
            $table->id();
            $table->string('gateway')->unique(); // midtrans, xendit, ipaymu, doku, duitku
            $table->string('display_name');
            $table->text('credentials'); // encrypted cast in model
            $table->enum('mode', ['sandbox', 'production'])->default('sandbox');
            $table->boolean('is_active')->default(false);
            $table->string('webhook_url')->nullable();
            $table->timestamps();
        });

        Schema::create('whatsapp_gateway_configs', function (Blueprint $table) {
            $table->id();
            $table->enum('provider', ['fonnte', 'wablast']);
            $table->text('api_key'); // encrypted cast in model
            $table->string('sender_number');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->uuid('owner_id')->nullable(); // null = global, set = owner override
            $table->timestamps();

            $table->index('owner_id');
        });

        Schema::create('email_gateway_configs', function (Blueprint $table) {
            $table->id();
            $table->string('driver')->default('smtp'); // smtp, mailgun, postmark, ses
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->nullable(); // encrypted cast in model
            $table->string('encryption')->nullable();
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_gateway_configs');
        Schema::dropIfExists('whatsapp_gateway_configs');
        Schema::dropIfExists('payment_gateway_configs');
    }
};
