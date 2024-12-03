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
    Schema::create('stock_entries', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('product_id');
        $table->integer('quantity');
        $table->decimal('purchase_price', 10, 2)->nullable();
        $table->unsignedBigInteger('user_id');
        $table->string('receipt_image_path')->nullable();
        $table->timestamps();

        // Llaves foráneas para relacionar la entrada de stock con un producto y un usuario
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_entries');
    }
};
