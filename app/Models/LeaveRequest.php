<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class LeaveRequest extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id', 'leave_type_id', 'start_date', 'end_date',
        'days_requested', 'reason', 'status', 'approved_by', 'approved_at', 'rejection_reason'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'approved_at' => 'datetime',
    ];
}