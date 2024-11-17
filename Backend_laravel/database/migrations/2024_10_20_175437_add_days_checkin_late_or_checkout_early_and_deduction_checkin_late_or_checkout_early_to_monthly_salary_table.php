<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDaysCheckinLateOrCheckoutEarlyAndDeductionCheckinLateOrCheckoutEarlyToMonthlySalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_salary', function (Blueprint $table) {
            $table->integer('days_checkin_late_or_checkout_early')->default(0)->after('overtime_salary'); // Thay 'some_column' bằng tên cột mà bạn muốn chèn sau
            $table->decimal('reduction_checkin_late_or_checkout_early', 10, 2)->default(0)->after('days_checkin_late_or_checkout_early'); // Cột kiểu số thập phân
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
            $table->dropColumn('days_checkin_late_or_checkout_early');
            $table->dropColumn('deduction_checkin_late_or_checkout_early');
        });
    }
}
