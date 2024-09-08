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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Khóa ngoại tới bảng users
            $table->decimal('basic_salary', 15, 2); // Lương cơ bản
            $table->decimal('bonus', 15, 2)->nullable(); // Thưởng (nếu có)
            $table->decimal('tax', 15, 2)->nullable(); // Thuế thu nhập cá nhân
            $table->decimal('social_insurance', 15, 2)->nullable(); // Bảo hiểm xã hội
            $table->decimal('deductions', 15, 2)->nullable(); // Các khoản khấu trừ khác
            $table->string('deduction_description')->nullable(); // Mô tả chi tiết các khoản khấu trừ
            $table->decimal('total_salary', 15, 2); // Tổng lương sau khi tính toán
            $table->date('salary_date'); // Ngày tính lương
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
