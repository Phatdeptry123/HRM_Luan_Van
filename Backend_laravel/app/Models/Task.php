<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'assigned_to',
        'due_date',
        'status'
    ];

    // Liên kết với bảng users (Người tạo công việc)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Liên kết với bảng users (Người được giao công việc)
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
