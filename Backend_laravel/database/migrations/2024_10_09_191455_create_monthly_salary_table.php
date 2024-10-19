<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlySalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_salary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->text('bonus_description')->nullable();
            $table->decimal('reduction', 15, 2)->default(0);
            $table->text('reduction_description')->nullable();
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('social_insurance', 15, 2)->default(0);
            $table->decimal('total_salary', 15, 2);
            $table->date('month');  // Chứa tháng và năm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_salary');
    }
}
