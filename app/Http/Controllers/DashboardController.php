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
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('roles');
        
        if ($user->hasAnyRole(['Super Admin', 'HR'])) {
            // HR / Super Admin Dashboard Stats
            $totalEmployees = Employee::count();
            $totalDepartments = Department::count();
            $pendingLeaves = LeaveRequest::where('status', 'pending')->count();
            
            $thisMonth = Carbon::now()->format('Y-m');
            $thisMonthPayroll = Payroll::where('month', $thisMonth)->sum('net_salary');
            $totalPayrollThisMonth = Payroll::where('month', $thisMonth)->count();

            $recentPayrolls = Payroll::with('employee')
                                ->latest()
                                ->take(5)
                                ->get();

            return view('dashboard.hr', compact(
                'totalEmployees', 
                'totalDepartments', 
                'pendingLeaves',
                'thisMonthPayroll',
                'totalPayrollThisMonth',
                'recentPayrolls'
            ));
        } 
        elseif ($user->hasRole('Manager')) {
            return view('dashboard.manager');
        } 
        else {
            return view('dashboard.employee');
        }
    }

    public function myProfile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $employee = $user->employee()->with(['department', 'position'])->first();

        if ($user->hasAnyRole(['Super Admin', 'HR'])) {
            return view('profiles.hr', compact('employee'));
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
            return back()->withErrors(['current_password' => 'Nenosiri la sasa si sahihi.']);
        }

        // Update password
         /** @var \App\Models\User $user */
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('my-profile')
                        ->with('success', 'Nenosiri limebadilishwa kwa mafanikio!');
    }
    
}
