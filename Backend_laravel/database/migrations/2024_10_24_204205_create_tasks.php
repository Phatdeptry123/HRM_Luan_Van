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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề công việc
            $table->text('description')->nullable(); // Mô tả chi tiết công việc
            $table->foreignId('created_by')->constrained('users'); // Người tạo công việc
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // Người được giao công việc
            $table->date('due_date')->nullable(); // Hạn hoàn thành
            $table->enum('status', ['todo','pending', 'in_progress', 'completed', 'cancelled'])->default('todo'); // Trạng thái công việc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_tasks');
    }
};
