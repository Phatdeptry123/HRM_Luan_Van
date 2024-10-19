<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMonthlySalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_salary', function (Blueprint $table) {
            $table->integer('working_days')->nullable(); // Thêm cột working_days
            $table->integer('overtime_hours')->nullable(); // Thêm cột overtime_hours
            $table->decimal('overtime_salary', total: 65)->nullable(); // Thêm cột overtime_salary
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_salary', function (Blueprint $table) {
            $table->dropColumn(['working_days', 'overtime_hours', 'overtime_salary']);
        });
    }
}
