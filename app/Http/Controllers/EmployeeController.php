<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department', 'position', 'user'])->latest()->paginate(10);
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
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'date_hired' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'basic_salary' => 'nullable|numeric|min:0',
            'role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Create User Account
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make('password'),   // Default password
        ]);

        // Assign Role
        if (!$request->role) {
            $user->assignRole('Employee');  // Default role
        } else {
            $user->assignRole($request->role); // Role selected
        }

        // Create Employee
        $data = $request->all();
        $data['user_id'] = $user->id;

        if ($request->filled('date_of_birth')) {
            $data['date_of_birth'] = $request->date_of_birth;
        } else {
            $data['date_of_birth'] = null; // Set to null if not provided
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('success', 'Mfanyakazi ameongezwa kwa mafanikio! Akaunti imeundwa (Default Password: password)');
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
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'date_hired' => 'required|date',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
            'basic_salary' => 'nullable|numeric|min:0',
            'role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::delete('public/' . $employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($data);

        // Update Role
        $employee->user->syncRoles([$request->role]);

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
