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
    Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('customer_id')->nullable();
        $table->decimal('total', 10, 2);
        $table->timestamps();

        // Llaves foráneas para relacionar la venta con un usuario y cliente
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
