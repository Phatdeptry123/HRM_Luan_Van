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
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Liên kết tới bảng users
            $table->foreignId('manager_id')->nullable()->constrained('users'); // Liên kết tới bảng users, có thể null
            $table->text('description')->nullable(); // Mô tả có thể để trống
            $table->integer('request_hour'); // Số giờ yêu cầu làm thêm
            $table->date('request_date'); // Ngày yêu cầu làm thêm
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Trạng thái yêu cầu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtimes');
    }
};
