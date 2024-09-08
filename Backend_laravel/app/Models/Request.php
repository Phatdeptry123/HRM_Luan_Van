<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    // Bảng tương ứng với model
    protected $table = 'requests';

    // Các thuộc tính có thể gán hàng loạt (mass assignable)
    protected $fillable = [
        'user_id',
        'type',
        'description',
        'request_date',
        'status',
        'manager_id',
    ];

    // Các thuộc tính được phép truy cập
    protected $casts = [
        'request_date' => 'date',
    ];

    // Quan hệ với model User (người gửi yêu cầu)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với model User (người quản lý duyệt)
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
