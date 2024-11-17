<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySalary extends Model
{
    use HasFactory;

    protected $table = 'monthly_salary';

    protected $fillable = [
        'user_id',
        'basic_salary',
        'bonus',
        'bonus_description',
        'reduction',
        'reduction_description',
        'tax',
        'social_insurance',
        'total_salary',
        'month',
        'working_days',
        'overtime_hours',
        'overtime_salary',
        'days_checkin_late_or_checkout_early',
        'deduction_checkin_late_or_checkout_early',
    ];

    // Quan hệ với bảng users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function whereYear($year)
    {
        return MonthlySalary::where('month', 'like', $year . '-%');
    }
}
