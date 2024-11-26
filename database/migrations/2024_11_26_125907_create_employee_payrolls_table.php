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
        Schema::create('employee_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->integer('present_day')->nullable();
            $table->decimal('salary')->nullable();
            $table->decimal('over_time')->nullable();
            $table->decimal('yearly_bonus')->nullable();
            $table->decimal('sales_comission')->nullable();
            $table->decimal('incentives')->nullable();
            $table->decimal('sss')->nullable();
            $table->decimal('pag_ibig')->nullable();
            $table->decimal('philhealth')->nullable();
            $table->decimal('tax')->nullable();
            $table->decimal('final_salary')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payrolls');
    }
};