<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Employee extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'user_id',
        'department_id',
        'position_id',
        'employee_number',
        'first_name',
        'last_name',
        'phone',
        'email',
        'date_of_birth',
        'date_hired',
        'gender',
        'marital_status',
        'photo',                   
        'basic_salary',
        'nssf_employee',
        'allowances',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // Auto-generate employee number on creation
    public static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->employee_number)) {
                $year = date('Y');
                $lastEmployee = self::where('employee_number', 'LIKE', "EMP-{$year}-%")
                                    ->orderBy('employee_number', 'desc')
                                    ->first();

                if ($lastEmployee) {
                    $lastNumber = (int) substr($lastEmployee->employee_number, -3);
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }

                $employee->employee_number = "EMP-{$year}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'date_hired' => 'date',
            'date_of_birth' => 'date',
        ];
    }

}