<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ==================== SUPER ADMIN ====================
        if ($user->hasRole('Super Admin')) {
            $totalEmployees     = Employee::count();
            $totalDepartments   = Department::count();
            $pendingLeaves      = LeaveRequest::where('status', 'pending')->count();
            $thisMonth          = now()->format('Y-m');
            $thisMonthPayroll   = Payroll::where('month', $thisMonth)->sum('net_salary');
            $activeEmployees    = Employee::where('status', 'active')->count();
            $inactiveEmployees  = Employee::where('status', '!=', 'active')->count();
            $recentActivities   = ActivityLog::with('user')->latest()->take(8)->get();

            return view('dashboard.admin', compact(
                'totalEmployees',
                'totalDepartments',
                'pendingLeaves',
                'thisMonthPayroll',
                'activeEmployees',
                'inactiveEmployees',
                'recentActivities'
            ));
        }

        // ==================== HR ====================
        if ($user->hasRole('HR')) {
            $totalEmployees     = Employee::count();
            $totalDepartments   = Department::count();
            $pendingLeaves      = LeaveRequest::where('status', 'pending')->count();
            $thisMonth          = now()->format('Y-m');
            $thisMonthPayroll   = Payroll::where('month', $thisMonth)->sum('net_salary');
            $totalPayrollCount  = Payroll::where('month', $thisMonth)->count();
            $recentHires        = Employee::latest()->take(5)->get();
            $pendingLeaveList   = LeaveRequest::with('employee.department', 'leaveType')
                ->where('status', 'pending')
                ->latest()->take(5)->get();

            return view('dashboard.hr', compact(
                'totalEmployees',
                'totalDepartments',
                'pendingLeaves',
                'thisMonthPayroll',
                'totalPayrollCount',
                'recentHires',
                'pendingLeaveList'
            ));
        }

        // ==================== MANAGER ====================
        if ($user->hasRole('Manager')) {
            $employee = $user->employee;
            $teamMembers = collect();
            $pendingTeamLeaves = 0;
            $onLeaveNow = 0;

            if ($employee) {
                $teamMembers = Employee::where('department_id', $employee->department_id)
                    ->where('id', '!=', $employee->id)
                    ->with('position')
                    ->get();

                $pendingTeamLeaves = LeaveRequest::whereIn('employee_id', $teamMembers->pluck('id'))
                    ->where('status', 'pending')
                    ->count();

                $onLeaveNow = LeaveRequest::whereIn('employee_id', $teamMembers->pluck('id'))
                    ->where('status', 'approved')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->count();
            }

            return view('dashboard.manager', compact(
                'teamMembers',
                'pendingTeamLeaves',
                'onLeaveNow'
            ));
        }

        // ==================== EMPLOYEE ====================
        $employee = $user->employee;
        $myLeaves = collect();
        $leaveBalance = 0;
        $currentLeave = null;
        $latestPayslip = null;

        if ($employee) {
            $myLeaves = LeaveRequest::where('employee_id', $employee->id)
                ->latest()->take(5)->get();

            $leaveBalance = 21; // Unaweza kuhesabu kutoka leave types baadaye
            $currentLeave = LeaveRequest::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            $latestPayslip = Payroll::where('employee_id', $employee->id)
                ->latest('month')->first();
        }

        return view('dashboard.employee', compact(
            'employee',
            'myLeaves',
            'leaveBalance',
            'currentLeave',
            'latestPayslip'
        ));
    }

    public function myProfile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $employee = $user->employee()->with(['department', 'position'])->first();

        if ($user->hasAnyRole('Super Admin')) {
            return view('profiles.admin', compact('employee'));
        } elseif ($user->hasRole('HR')) {
            return view('profiles.manager', compact('employee'));
        } elseif ($user->hasRole('Manager')) {
            return view('profiles.manager', compact('employee'));
        } else {
            return view('profiles.employee', compact('employee'));
        }
    }

    public function editProfile()
    {
        $employee = Auth::user()->employee;
        return view('profiles.edit', compact('employee'));
    }

    public function updateProfile(Request $request)
    {
        $employee = Auth::user()->employee;

        $request->validate([
            'phone' => 'required|string',
            'date_of_birth' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['phone', 'date_of_birth']);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::delete('public/' . $employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($data);

        return redirect()->route('my-profile')
            ->with('success', 'Profile imehaririwa!');
    }

    public function changePassword()
    {
        return view('profiles.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        /** @var \App\Models\User $user */
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('my-profile')
            ->with('success', 'Password updated successfully!');
    }
}
