<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('owner_id');
            $table->unsignedBigInteger('package_id');
            $table->integer('token_amount');
            $table->integer('amount_idr');
            $table->enum('gateway', ['midtrans', 'xendit', 'ipaymu', 'doku', 'duitku']);
            $table->string('gateway_order_id')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')
                ->references('id')
                ->on('owners')
                ->onDelete('cascade');

            $table->foreign('package_id')
                ->references('id')
                ->on('token_packages')
                ->onDelete('cascade');

            $table->index(['owner_id', 'status']);
            $table->index('gateway_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('token_orders');
    }
};
