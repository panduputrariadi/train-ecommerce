<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('payment_method_id')
                ->constrained('payment_methods')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('bank_account_id')
                ->nullable()
                ->constrained('bank_accounts')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('verified_by')->nullable();

            $table->unsignedBigInteger('amount');
            $table->string('status');
            $table->text('note')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('verified_by')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
