<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeStatusHistory extends Model
{
    protected $fillable = [
        'employee_id',
        'old_status',
        'new_status',
        'changed_by',
        'reason',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}