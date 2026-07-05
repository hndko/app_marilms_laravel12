<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('question_count')->default(0);
            $table->integer('option_count')->default(4);
            $table->integer('duration_minutes')->nullable(); // null = use default per question
            $table->integer('passing_score')->default(70);
            $table->integer('retry_limit')->default(0); // 0 = unlimited
            $table->boolean('is_public')->default(true);
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->timestamp('deadline_at')->nullable();
            $table->text('prompt_topic')->nullable(); // original AI prompt topic
            $table->timestamps();

            $table->index('status');
            $table->index('category');
            $table->index('deadline_at');
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->integer('order')->default(0);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable();
            $table->timestamps();

            $table->index(['quiz_id', 'order']);
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->text('explanation')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('question_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');
    }
};
