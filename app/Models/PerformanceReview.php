<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use Loggable;
    protected $fillable = [
        'employee_id',
        'reviewed_by',
        'period',
        'rating',
        'strengths',
        'weaknesses',
        'recommendations',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}