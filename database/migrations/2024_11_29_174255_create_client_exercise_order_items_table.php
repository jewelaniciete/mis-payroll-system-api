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
        Schema::create('client_exercise_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exercise_order_id');
            $table->foreign('exercise_order_id')->references('id')->on('client_exercise_orders')->onDelete('cascade');
            $table->unsignedBigInteger('exercise_id');
            $table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_exercise_order_items');
    }
};
