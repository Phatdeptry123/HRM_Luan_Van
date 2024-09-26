<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date'); // Ngày chấm công
            $table->time('check_in')->nullable(); // Giờ check-in
            $table->time('check_out')->nullable(); // Giờ check-out
            $table->enum('status', ['on_time', 'late', 'absent'])->default('on_time'); // Trạng thái chấm công
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();

            // Khóa ngoại user_id tham chiếu tới bảng users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
