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
        Schema::create('extra_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_id');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            // Llave foránea para relacionar el cargo adicional con el gasto
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_charges');
    }
};
