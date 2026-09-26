<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Notifications\EmployeeStatusChangedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->latest()->paginate(10)->withQueryString();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $roles = Role::all();   // HR chooses role for employee
        return view('employees.create', compact('departments', 'positions', 'roles'));
    }

    public function store(Request $request)
    {
        // Minimum user's age = 15 years
        $minAgeDate = now()->subYears(15)->format('Y-m-d');

        // 2. Validation
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:employees,email',
            'phone'         => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'position_id'   => 'required|exists:positions,id',
            'date_hired'    => 'required|date',
            'contract_end_date' => 'nullable|date', 
            'date_of_birth' => 'nullable|date|before_or_equal:' . $minAgeDate,
            'gender'        => 'required|in:Male,Female',
            'basic_salary'  => 'required|numeric|min:0',
            'role'          => 'required', 
            'status'        => 'required|in:active,inactive,terminated',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'date_of_birth.before_or_equal' => 'Employee must be at least 15 years old.',
        ]);

        
        $user = User::create([
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make('password'),
        ]);

        // Assign Role
        if ($request->filled('role')) {
            $user->assignRole($request->role);
        } else {
            $user->assignRole('Employee');
        }

        // Preparing Employee data
        $data = $request->except(['_token', 'role']);
        $data['user_id'] = $user->id;

        // Null if not provided
        $data['date_of_birth'] = $request->filled('date_of_birth') ? $request->date_of_birth : null;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('success', 'Employee added successfully!');
    }


    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $positions = Position::all();
        $roles = Role::all();
        return view('employees.edit', compact('employee', 'departments', 'positions', 'roles'));
    }

    public function update(Request $request, Employee $employee)
    {
        // Minimum user's age = 15 years
        $minAgeDate = now()->subYears(15)->format('Y-m-d');

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'date_hired' => 'required|date',
            'date_of_birth' => 'nullable|date|before_or_equal:' . $minAgeDate,
            'gender' => 'required|in:Male,Female',
            'basic_salary' => 'nullable|numeric|min:0',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,on_leave,inactive,terminated',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                // Custom error message if the age validation requirement fails
                'date_of_birth.before_or_equal' => 'Employee must be at least 15 years old.',
            ]
        );

        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::delete('public/' . $employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        // Log status change
        if ($request->status !== $employee->status) {
            \App\Models\EmployeeStatusHistory::create([
                'employee_id' => $employee->id,
                'old_status'  => $employee->status,
                'new_status'  => $request->status,
                'changed_by'  => auth()->id(),
                'reason'      => $request->status_reason ?? null,
                'changed_at'  => now(),
            ]);
        }

        $oldStatus = $employee->status;

        $employee->update($data);

        // Update Role
        $employee->user->syncRoles([$request->role]);

        if ($request->status !== $oldStatus) {
            $admins = User::role(['Super Admin', 'HR'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new EmployeeStatusChangedNotification($employee, $oldStatus, $request->status));
            }
        }

        return redirect()->route('employees.index')
            ->with('success', "Employee's info updated successfully!");
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::delete('public/' . $employee->photo);
        }
        $employee->user->delete();   // Delete user account
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', "Employee deleted successfully!");
    }
}