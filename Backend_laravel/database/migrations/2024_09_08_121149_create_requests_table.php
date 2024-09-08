<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Người gửi yêu cầu
            $table->enum('type', ['leave', 'remote', 'late']); // Loại yêu cầu
            $table->text('description')->nullable(); // Mô tả yêu cầu
            $table->date('request_date'); // Ngày gửi yêu cầu
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Trạng thái yêu cầu
            $table->foreignId('manager_id')->nullable()->constrained('users'); // Người quản lý duyệt
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
}
