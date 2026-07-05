<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('owner_id');
            $table->enum('type', ['debit', 'credit']);
            $table->integer('amount');
            $table->enum('source', ['register', 'purchase', 'manual_topup', 'quiz_generate']);
            $table->string('reference_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at');

            $table->foreign('owner_id')
                ->references('id')
                ->on('owners')
                ->onDelete('cascade');

            $table->index(['owner_id', 'created_at']);
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('token_transactions');
    }
};
