<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('owner_token_balances', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner_id')->unique();
            $table->integer('balance')->default(0);
            $table->boolean('is_unlimited')->default(false);
            $table->timestamps();

            $table->foreign('owner_id')
                ->references('id')
                ->on('owners')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owner_token_balances');
    }
};
