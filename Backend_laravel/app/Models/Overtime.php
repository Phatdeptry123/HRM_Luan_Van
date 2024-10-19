<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'overtimes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'manager_id',
        'description',
        'request_hour',
        'request_date',
        'status',
    ];

    /**
     * Get the user who requested the overtime.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the manager who approved/rejected the overtime.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
