<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Membuat tabel quiz_participants, quiz_attempts, dan quiz_answers di database central.
 * Sebelumnya tabel-tabel ini ada di database tenant terpisah.
 * Kini disatukan dengan tambahan kolom tenant_id.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_participants', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            // user_id merujuk ke tenant_users.id
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['tenant_id', 'quiz_id', 'user_id']);
            $table->index('tenant_id');
        });

        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            // user_id merujuk ke tenant_users.id
            $table->unsignedBigInteger('user_id');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->integer('total_duration_seconds');
            $table->enum('status', ['in_progress', 'submitted', 'timeout', 'force_ended'])->default('in_progress');
            $table->enum('end_reason', ['manual', 'time_up', 'tab_switch', 'browser_close', 'admin_force'])->nullable();
            $table->boolean('is_flagged')->default(false);
            $table->integer('score')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index(['tenant_id', 'user_id', 'quiz_id']);
            $table->index('status');
            $table->index('started_at');
        });

        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('selected_option_id')->nullable()->constrained('question_options')->onDelete('set null');
            $table->boolean('is_correct')->default(false);
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
            $table->index('tenant_id');
            $table->index('attempt_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_participants');
    }
};
