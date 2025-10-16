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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('description');
            $table->integer('price');
            $table->integer('stock');
            $table->string('photo')->nullable();
            $table->boolean('is_discount')->default(false);
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
