<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    // Khai báo tên bảng nếu không trùng với tên model
    protected $table = 'salaries';

    // Khai báo các cột có thể được mass-assigned
    protected $fillable = [
        'user_id',
        'basic_salary',
        'bonus',
        'tax',
        'social_insurance',
        'deductions',
        'deduction_description',
        'total_salary',
        'salary_date',
    ];

    /**
     * Mối quan hệ giữa Salary và User (1-N).
     * Mỗi Salary sẽ thuộc về một User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryDetails()
    {
        return $this->hasMany(SalaryDetail::class);
    }
}
