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
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->integer('stock');
            $table->integer('stock_min');
            $table->string('image_path')->nullable();
            $table->timestamps();

            // Llaves foráneas
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
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
