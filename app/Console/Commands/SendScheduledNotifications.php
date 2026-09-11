<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use App\Notifications\BirthdayNotification;
use App\Notifications\ContractExpiringNotification;
use App\Notifications\LeaveStartingSoonNotification;
use App\Notifications\LowLeaveBalanceNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendScheduledNotifications extends Command
{
    protected $signature = 'notifications:send-scheduled';
    protected $description = 'Send daily scheduled HR notifications';

    public function handle()
    {
        $this->info('Sending scheduled notifications...');

        $this->notifyContractExpiring();
        $this->notifyLeaveStartingSoon();
        $this->notifyLowLeaveBalance();
        $this->notifyBirthdays();

        $this->info('Done.');
        return Command::SUCCESS;
    }

    protected function notifyContractExpiring()
    {
        $hrUsers = User::role(['Super Admin', 'HR'])->get();

        // Contracts ending in 30, 14, or 7 days
        foreach ([30, 14, 7] as $days) {
            $targetDate = now()->addDays($days)->toDateString();

            $employees = Employee::where('status', 'active')
                ->whereDate('contract_end_date', $targetDate)
                ->get();

            foreach ($employees as $employee) {
                Notification::send($hrUsers, new ContractExpiringNotification($employee, $days));
            }
        }
    }

    protected function notifyLeaveStartingSoon()
    {
        // Leaves starting tomorrow
        $leaves = LeaveRequest::with(['employee.user', 'employee.department'])
            ->where('status', 'approved')
            ->whereDate('start_date', now()->addDay()->toDateString())
            ->get();

        foreach ($leaves as $leave) {
            // Employee
            if ($leave->employee?->user) {
                $leave->employee->user->notify(new LeaveStartingSoonNotification($leave));
            }

            // Manager of department
            if ($leave->employee?->department_id) {
                $managers = User::role('Manager')
                    ->whereHas('employee', function ($q) use ($leave) {
                        $q->where('department_id', $leave->employee->department_id);
                    })
                    ->get();

                Notification::send($managers, new LeaveStartingSoonNotification($leave));
            }
        }
    }

    protected function notifyLowLeaveBalance()
    {
        $leaveTypes = LeaveType::all();
        $year = now()->year;
        $threshold = 3; // notify if remaining <= 3 days

        $employees = Employee::with('user')->where('status', 'active')->get();

        foreach ($employees as $employee) {
            if (!$employee->user) continue;

            foreach ($leaveTypes as $type) {
                $used = LeaveRequest::where('employee_id', $employee->id)
                    ->where('leave_type_id', $type->id)
                    ->where('status', 'approved')
                    ->whereYear('start_date', $year)
                    ->sum('days_requested');

                $total = $type->max_days_per_year ?? 0;
                $remaining = max(0, $total - $used);

                if ($total > 0 && $remaining <= $threshold && $remaining >= 0) {
                    $employee->user->notify(
                        new LowLeaveBalanceNotification($type->name, $remaining)
                    );
                }
            }
        }
    }

    protected function notifyBirthdays()
    {
        // Requires birth_date column on employees
        if (!\Schema::hasColumn('employees', 'birth_date')) {
            return;
        }

        $hrUsers = User::role(['Super Admin', 'HR'])->get();

        $employees = Employee::where('status', 'active')
            ->whereMonth('birth_date', now()->month)
            ->whereDay('birth_date', now()->day)
            ->get();

        foreach ($employees as $employee) {
            Notification::send($hrUsers, new BirthdayNotification($employee));
        }
    }
}