<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    use HasFactory;

    // Các cột có thể được ghi dữ liệu vào
    protected $fillable = [
        'salary_id',
        'allowance',
        'overtime',
        'bonus',
        'other_deductions',
        'final_salary',
        'description',
    ];

    // Định nghĩa mối quan hệ với bảng Salary
    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }
}
