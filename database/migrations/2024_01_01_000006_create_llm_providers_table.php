<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('llm_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('provider_type', ['openrouter', 'deepseek', 'custom'])->default('openrouter');
            $table->string('base_url');
            $table->text('api_key'); // encrypted cast in model
            $table->string('model');
            $table->integer('max_tokens')->default(4000);
            $table->decimal('temperature', 3, 2)->default(0.70);
            $table->integer('priority')->default(1);
            $table->enum('status', ['active', 'fallback', 'inactive'])->default('active');
            $table->uuid('owner_id')->nullable(); // null = global, set = owner override
            $table->timestamps();

            $table->index('priority');
            $table->index('status');
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('llm_providers');
    }
};
