<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Payroll;
use App\Models\ActivityLog;
use App\Models\Attendance;
use App\Models\PublicHoliday;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $nextHoliday = null;

        $nextHoliday = PublicHoliday::where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->first();

        // ==================== SUPER ADMIN ====================
        if ($user->hasRole('Super Admin')) {
            $totalEmployees     = Employee::count();
            $totalDepartments   = Department::count();
            $pendingLeaves      = LeaveRequest::where('status', 'pending')->count();
            $thisMonth          = now()->format('Y-m');
            $thisMonthPayroll   = Payroll::where('month', $thisMonth)->sum('net_salary');
            $activeEmployees    = Employee::where('status', 'active')->count();
            $inactiveEmployees  = Employee::where('status', '!=', 'active')->count();
            $recentActivities   = ActivityLog::with('user')->latest()->take(3)->get();

            $activeEmployees    = Employee::where('status', 'active')->count();
            $inactiveEmployees  = Employee::where('status', 'inactive')->count();
            $terminatedEmployees = Employee::where('status', 'terminated')->count();

            // Staff Turnover Rate (this month)
            $startOfMonth = now()->startOfMonth();
            $endOfMonth   = now()->copy()->endOfMonth();

            // Left this month (from history)
            $leftThisMonth = \App\Models\EmployeeStatusHistory::where('new_status', 'terminated')
                ->whereBetween('changed_at', [$startOfMonth, $endOfMonth])
                ->count();

            // Start headcount: all employees that existed before this month minus already terminated before this month
            $totalCreatedBefore = \App\Models\Employee::where('created_at', '<', $startOfMonth)->count();
            $terminatedBefore   = \App\Models\EmployeeStatusHistory::where('new_status', 'terminated')
                ->where('changed_at', '<', $startOfMonth)
                ->count();
            $employeesAtStart = max(0, $totalCreatedBefore - $terminatedBefore);

            // End headcount: current active + inactive
            $employeesAtEnd = \App\Models\Employee::whereIn('status', ['active', 'inactive'])->count();

            $averageEmployees = ($employeesAtStart + $employeesAtEnd) / 2;
            if ($averageEmployees <= 0) $averageEmployees = 1;

            $turnoverRate = round(($leftThisMonth / $averageEmployees) * 100, 1);
            $terminatedThisMonth = $leftThisMonth;

            // Latest Announcements
            $latestAnnouncements = \App\Models\Announcement::visible()
                ->latest('published_at')
                ->take(3)
                ->get();

            return view('dashboard.admin', compact(
                'totalEmployees',
                'totalDepartments',
                'pendingLeaves',
                'thisMonthPayroll',
                'activeEmployees',
                'inactiveEmployees',
                'recentActivities',
                'nextHoliday',
                'turnoverRate',
                'terminatedEmployees',
                'terminatedThisMonth',
                'latestAnnouncements'
            ));
        }

        // ==================== HR ====================
        if ($user->hasRole('HR')) {
            $currentMonth = now()->format('Y-m');
            $totalEmployees     = Employee::count();
            $totalDepartments   = Department::count();
            $pendingLeaves      = LeaveRequest::where('status', 'pending')->count();
            $payrollThisMonth   = Payroll::where('month', $currentMonth);
            $totalPayrollCount  = Payroll::where('month', $currentMonth)->count();
            $totalNetThisMonth   = $payrollThisMonth->sum('net_salary');
            $totalGrossThisMonth = $payrollThisMonth->sum('gross_salary');
            $totalNSSF           = $payrollThisMonth->sum('nssf_employee');
            $totalNHIF           = $payrollThisMonth->sum('nhif');
            $totalPAYE           = $payrollThisMonth->sum('paye');

            // New Hires this month
            $newHiresThisMonth  = Employee::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            // Upcoming Leaves (next 7 days)
            $upcomingLeaves = LeaveRequest::with(['employee.department', 'leaveType'])
                ->where('status', 'approved')
                ->whereDate('start_date', '>', now())
                ->whereDate('start_date', '<=', now()->addDays(7))
                ->orderBy('start_date')
                ->take(8)
                ->get();

            $recentHires = Employee::latest()->take(5)->get();

            $pendingLeaveList = LeaveRequest::with('employee.department', 'leaveType')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            // Employees per Department (for pie chart)
            $employeesPerDept = Department::withCount('employees')
                ->get()
                ->map(function ($dept) {
                    return [
                        'name'  => $dept->name,
                        'count' => $dept->employees_count,
                    ];
                });

            // Expiring Contracts (next 30 days)
            $expiringContracts = Employee::with('department')
                ->whereNotNull('contract_end_date')
                ->whereDate('contract_end_date', '>=', now())
                ->whereDate('contract_end_date', '<=', now()->addDays(30))
                ->orderBy('contract_end_date')
                ->get();

            // Last 6 months for chart
            $months = collect();
            for ($i = 5; $i >= 0; $i--) {
                $m = now()->subMonths($i)->format('Y-m');
                $months->push([
                    'month' => $m,
                    'label' => now()->subMonths($i)->format('M Y'),
                    'net'   => Payroll::where('month', $m)->sum('net_salary'),
                    'gross' => Payroll::where('month', $m)->sum('gross_salary'),
                ]);
            }

            // Latest Announcements
            $latestAnnouncements = \App\Models\Announcement::visible()
                ->latest('published_at')
                ->take(3)
                ->get();

            return view('dashboard.hr', compact(
                'totalEmployees',
                'totalDepartments',
                'pendingLeaves',
                'totalPayrollCount',
                'newHiresThisMonth',
                'upcomingLeaves',
                'recentHires',
                'nextHoliday',
                'pendingLeaveList',
                'employeesPerDept',
                'expiringContracts',
                'totalNetThisMonth',
                'totalGrossThisMonth',
                'totalNSSF',
                'totalNHIF',
                'totalPAYE',
                'months',
                'latestAnnouncements'
            ));
        }

        // ==================== MANAGER ====================
        if ($user->hasRole('Manager')) {
            $employee = $user->employee;
            $teamMembers = collect();
            $pendingTeamLeaves = 0;
            $onLeaveNow = 0;
            $upcomingTeamLeaves = collect();

            if ($employee) {
                $teamMembers = Employee::where('department_id', $employee->department_id)
                    ->where('id', '!=', $employee->id)
                    ->with('position')
                    ->get();

                $teamIds = $teamMembers->pluck('id');

                // Pending leaves
                $pendingTeamLeaves = LeaveRequest::whereIn('employee_id', $teamIds)
                    ->where('status', 'pending')
                    ->count();

                // Currently on leave
                $onLeaveNow = LeaveRequest::whereIn('employee_id', $teamIds)
                    ->where('status', 'approved')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->count();

                // Upcoming Team Leaves (approved + start_date in future)
                $upcomingTeamLeaves = LeaveRequest::with(['employee', 'leaveType'])
                    ->whereIn('employee_id', $teamIds)
                    ->where('status', 'approved')
                    ->whereDate('start_date', '>', now())
                    ->orderBy('start_date')
                    ->take(7)
                    ->get();

                // Latest Announcements
                $latestAnnouncements = \App\Models\Announcement::visible()
                    ->latest('published_at')
                    ->take(3)
                    ->get();
            }

            return view('dashboard.manager', compact(
                'teamMembers',
                'pendingTeamLeaves',
                'onLeaveNow',
                'upcomingTeamLeaves',
                'nextHoliday',
                'latestAnnouncements'
            ));
        }

        // ==================== EMPLOYEE ====================
        $employee = $user->employee;
        $myLeaves = collect();
        $leaveBalances = collect();
        $currentLeave = null;
        $latestPayslip = null;
        $myAttendance = collect();
        $pendingLeaveCount = 0;

        if ($employee) {
            $myLeaves = LeaveRequest::with('leaveType')
                ->where('employee_id', $employee->id)
                ->latest()
                ->take(5)
                ->get();


            // ===== LEAVE BALANCE CALCULATION =====
            $leaveTypes = LeaveType::all();
            $currentYear = now()->year;

            $leaveBalances = $leaveTypes->map(function ($type) use ($employee, $currentYear) {
                $usedDays = LeaveRequest::where('employee_id', $employee->id)
                    ->where('leave_type_id', $type->id)
                    ->where('status', 'approved')
                    ->whereYear('start_date', $currentYear)
                    ->sum('days_requested');

                $totalDays = $type->max_days_per_year ?? 0;
                $remaining = max(0, ($totalDays ?? 0) - $usedDays);

                return [
                    'name'      => $type->name,
                    'total'     => $totalDays,
                    'used'      => $usedDays,
                    'remaining' => $remaining,
                ];
            });

            $currentLeave = LeaveRequest::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            $latestPayslip = Payroll::where('employee_id', $employee->id)
                ->latest('month')
                ->first();

            $myAttendance = Attendance::where('employee_id', $employee->id)
                ->where('date', '>=', now()->subDays(6)->toDateString())
                ->orderBy('date', 'desc')
                ->get();

            $pendingLeaveCount = LeaveRequest::where('employee_id', $employee->id)
                ->where('status', 'pending')
                ->count();

            // Latest Announcements
            $latestAnnouncements = \App\Models\Announcement::visible()
                ->latest('published_at')
                ->take(3)
                ->get();
        }

        return view('dashboard.employee', compact(
            'employee',
            'myLeaves',
            'leaveBalances',
            'currentLeave',
            'latestPayslip',
            'myAttendance',
            'nextHoliday',
            'pendingLeaveCount',
            'latestAnnouncements'
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
            ->with('success', 'Profile updated successfully!');
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

    public function myTeamMembers()
    {
        return view('profiles.change-password');
    }

    // ==================== MANAGER TEAM PAGES ====================
    public function teamMembers()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('Manager') || !$user->employee) {
            abort(403);
        }

        $teamMembers = Employee::where('department_id', $user->employee->department_id)
            ->where('id', '!=', $user->employee->id)
            ->with(['position', 'department'])
            ->get();

        return view('manager.team-members', compact('teamMembers'));
    }

    public function teamLeaves()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('Manager') || !$user->employee) {
            abort(403);
        }

        $teamIds = Employee::where('department_id', $user->employee->department_id)
            ->where('id', '!=', $user->employee->id)
            ->pluck('id');

        $pendingLeaves = LeaveRequest::with(['employee', 'leaveType'])
            ->whereIn('employee_id', $teamIds)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $upcomingLeaves = LeaveRequest::with(['employee', 'leaveType'])
            ->whereIn('employee_id', $teamIds)
            ->where('status', 'approved')
            ->whereDate('start_date', '>', now())
            ->orderBy('start_date')
            ->get();

        $onLeaveNow = LeaveRequest::with(['employee', 'leaveType'])
            ->whereIn('employee_id', $teamIds)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();

        return view('manager.team-leaves', compact('pendingLeaves', 'upcomingLeaves', 'onLeaveNow'));
    }

    public function teamAttendance()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('Manager') || !$user->employee) {
            abort(403);
        }

        $teamMembers = Employee::where('department_id', $user->employee->department_id)
            ->where('id', '!=', $user->employee->id)
            ->get();

        $teamIds = $teamMembers->pluck('id');

        // Attendance (Last 7 days)
        $startDate = now()->subDays(6)->startOfDay();
        $endDate   = now()->endOfDay();

        $attendances = Attendance::with('employee')
            ->whereIn('employee_id', $teamIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('employee_id');

        return view('manager.team-attendance', compact('teamMembers', 'attendances', 'startDate', 'endDate'));
    }

    // ==================== MARK ATTENDANCE ====================

    public function markAttendanceForm()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();


        if (!$user->hasAnyRole(['Manager', 'HR', 'Super Admin'])) {
            abort(403);
        }

        if ($user->hasRole('Manager') && $user->employee) {
            // Manager → only team
            $employees = Employee::where('department_id', $user->employee->department_id)
                ->where('id', '!=', $user->employee->id)
                ->where('status', 'active')
                ->orderBy('first_name')
                ->get();
        } else {
            // HR / Super Admin 
            $employees = Employee::where('status', 'active')
                ->orderBy('first_name')
                ->get();
        }

        $today = now()->format('Y-m-d');

        return view('manager.mark-attendance', compact('employees', 'today'));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'attendances' => 'required|array|min:1',
            'attendances.*.employee_id' => 'required|exists:employees,id',
            'attendances.*.status' => 'required|in:present,absent,late,half_day,on_leave',
            'attendances.*.check_in' => 'nullable|date_format:H:i',
            'attendances.*.check_out' => 'nullable|date_format:H:i|after:attendances.*.check_in',
            'attendances.*.notes' => 'nullable|string|max:255',
        ], [
            'date.before_or_equal' => 'You cannot set attendance for a later date.',
            'attendances.*.check_out.after' => 'Check-out must be after check-in.',
            'attendances.*.status.in' => 'Status is invalid.',
        ]);

        $date = $request->date;
        $count = 0;
        /** @var \App\Models\User $user */
        $user = Auth::user();

        foreach ($request->attendances as $data) {
            // Security: Manager (only team)
            if ($user->hasRole('Manager') && $user->employee) {
                $employee = Employee::find($data['employee_id']);
                if (!$employee || $employee->department_id !== $user->employee->department_id) {
                    continue; // Skip employee with no team
                }
            }

            // Status validation
            $checkIn  = $data['check_in'] ?? null;
            $checkOut = $data['check_out'] ?? null;

            // Status is Absent or On Leave → check_in/out must be null
            if (in_array($data['status'], ['absent', 'on_leave'])) {
                $checkIn  = null;
                $checkOut = null;
            }

            Attendance::updateOrCreate(
                [
                    'employee_id' => $data['employee_id'],
                    'date'        => $date,
                ],
                [
                    'status'    => $data['status'],
                    'check_in'  => $checkIn,
                    'check_out' => $checkOut,
                    'notes'     => $data['notes'] ?? null,
                ]
            );

            $count++;
        }

        if ($count === 0) {
            return redirect()->back()->with('error', 'No attendance saved. Check your permissions.');
        }

        return redirect()->route('team.attendance')
            ->with('success', "Attendance for {$date} has been saved for {$count} employees.");
    }

    // HR / Admin Attendance
    public function hrAttendance(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403);
        }

        $date = $request->date ?? now()->format('Y-m-d');
        $departmentId = $request->department_id;

        $query = Employee::with(['department', 'position'])
            ->where('status', 'active');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $employees = $query->orderBy('first_name')->get();

        $attendances = Attendance::where('date', $date)
            ->whereIn('employee_id', $employees->pluck('id'))
            ->get()
            ->keyBy('employee_id');

        $departments = Department::orderBy('name')->get();

        // Summary counts
        $summary = [
            'present'  => $attendances->where('status', 'present')->count(),
            'late'     => $attendances->where('status', 'late')->count(),
            'absent'   => $attendances->where('status', 'absent')->count(),
            'half_day' => $attendances->where('status', 'half_day')->count(),
            'on_leave' => $attendances->where('status', 'on_leave')->count(),
            'not_marked' => $employees->count() - $attendances->count(),
        ];

        return view('attendance.hr-overview', compact(
            'employees',
            'attendances',
            'departments',
            'date',
            'departmentId',
            'summary'
        ));
    }

    // Leave Usage Report
    public function leaveUsageReport(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403);
        }

        $year = $request->year ?? now()->year;

        // Leave usage by Leave Type
        $byLeaveType = LeaveType::withCount([
            'leaveRequests as approved_count' => function ($q) use ($year) {
                $q->where('status', 'approved')->whereYear('start_date', $year);
            }
        ])
            ->withSum([
                'leaveRequests as total_days' => function ($q) use ($year) {
                    $q->where('status', 'approved')->whereYear('start_date', $year);
                }
            ], 'days_requested')
            ->get();

        // Leave usage by Department
        $byDepartment = Department::withCount([
            'employees as employee_count'
        ])
            ->get()
            ->map(function ($dept) use ($year) {
                $employeeIds = $dept->employees()->pluck('id');
                $totalDays = LeaveRequest::whereIn('employee_id', $employeeIds)
                    ->where('status', 'approved')
                    ->whereYear('start_date', $year)
                    ->sum('days_requested');
                $requestCount = LeaveRequest::whereIn('employee_id', $employeeIds)
                    ->where('status', 'approved')
                    ->whereYear('start_date', $year)
                    ->count();

                return [
                    'name'          => $dept->name,
                    'employee_count' => $dept->employee_count,
                    'request_count' => $requestCount,
                    'total_days'    => $totalDays,
                ];
            });

        // Monthly trend (approved leave days per month)
        $monthlyTrend = collect();
        for ($m = 1; $m <= 12; $m++) {
            $days = LeaveRequest::where('status', 'approved')
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $m)
                ->sum('days_requested');
            $monthlyTrend->push([
                'month' => Carbon::create()->month($m)->format('M'),
                'days'  => $days,
            ]);
        }

        return view('reports.leave-usage', compact(
            'byLeaveType',
            'byDepartment',
            'monthlyTrend',
            'year'
        ));
    }
}
