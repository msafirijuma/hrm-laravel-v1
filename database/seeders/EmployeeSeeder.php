<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();
        $positions = Position::all();

        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@company.com',
                'password' => Hash::make('password'),
                'role' => 'Super Admin',
                'employee' => [
                    'employee_number' => 'ADM001',
                    'first_name' => 'Admin',
                    'last_name' => 'User',
                    'phone' => '0711111111',
                    'email' => 'admin@company.com',
                    'date_of_birth' => '1990-01-01',
                    'date_hired' => '2022-01-01',
                    'gender' => 'Male',
                    'basic_salary' => 1200000,
                    'status' => 'active',
                    'department_id' => $departments[0]->id ?? 1,
                    'position_id' => $positions[0]->id ?? 1,
                ]
            ],
            [
                'name' => 'John Kamanda',
                'email' => 'manager@company.com',
                'password' => Hash::make('password'),
                'role' => 'Manager',
                'employee' => [
                    'employee_number' => 'MGR001',
                    'first_name' => 'John',
                    'last_name' => 'Kamanda',
                    'phone' => '0745123456',
                    'email' => 'manager@company.com',
                    'date_of_birth' => '1992-08-15',
                    'date_hired' => '2023-03-01',
                    'gender' => 'Male',
                    'basic_salary' => 950000,
                    'status' => 'active',
                    'department_id' => $departments[2]->id ?? 3,   
                    'position_id' => $positions[2]->id ?? 3,
                ]
            ],
            [
                'name' => 'Ahmed Msafiri',
                'email' => 'ahmed@company.com',
                'password' => Hash::make('password'),
                'role' => 'HR',
                'employee' => [
                    'employee_number' => 'EMP001',
                    'first_name' => 'Ahmed',
                    'last_name' => 'Msafiri',
                    'phone' => '0712345678',
                    'email' => 'ahmed@company.com',
                    'date_of_birth' => '1995-05-15',
                    'date_hired' => '2023-01-10',
                    'gender' => 'Male',
                    'basic_salary' => 850000,
                    'status' => 'active',
                    'department_id' => $departments[0]->id ?? 1,
                    'position_id' => $positions[0]->id ?? 1,
                ]
            ],
            [
                'name' => 'Hamissa Adams',
                'email' => 'hamissa@company.com',
                'password' => Hash::make('password'),
                'role' => 'Employee',
                'employee' => [
                    'employee_number' => 'EMP002',
                    'first_name' => 'Hamissa',
                    'last_name' => 'Adams',
                    'phone' => '0745123456',
                    'email' => 'hamissa@company.com',
                    'date_of_birth' => '1992-08-15',
                    'date_hired' => '2023-03-01',
                    'gender' => 'Male',
                    'basic_salary' => 800000,
                    'status' => 'active',
                    'department_id' => $departments[2]->id ?? 3,   // IT Department
                    'position_id' => $positions[2]->id ?? 3,
                ]
            ],
            [
                'name' => 'Ashura Mwinyi',
                'email' => 'ashura@company.com',
                'password' => Hash::make('password'),
                'role' => 'Employee',
                'employee' => [
                    'employee_number' => 'EMP003',
                    'first_name' => 'Ashura',
                    'last_name' => 'Mwinyi',
                    'phone' => '0777128080',
                    'email' => 'ashura@company.com',
                    'date_of_birth' => '1992-08-15',
                    'date_hired' => '2023-03-01',
                    'gender' => 'Male',
                    'basic_salary' => 350000,
                    'status' => 'active',
                    'department_id' => $departments[2]->id ?? 3,   // IT Department
                    'position_id' => $positions[2]->id ?? 3,
                ]
            ],
            [
                'name' => 'Sarah Mwangi',
                'email' => 'sarah@company.com',
                'password' => Hash::make('password'),
                'role' => 'Employee',
                'employee' => [
                    'employee_number' => 'EMP004',
                    'first_name' => 'Sarah',
                    'last_name' => 'Mwangi',
                    'phone' => '0756789123',
                    'email' => 'sarah@company.com',
                    'date_of_birth' => '1997-11-05',
                    'date_hired' => '2024-01-15',
                    'gender' => 'Female',
                    'basic_salary' => 580000,
                    'status' => 'active',
                    'department_id' => $departments[3]->id ?? 4,   // Sales
                    'position_id' => $positions[5]->id ?? 6,
                ]
           ],
           [
                'name' => 'David Kimaro',
                'email' => 'david@company.com',
                'password' => Hash::make('password'),
                'role' => 'Employee',
                'employee' => [
                    'employee_number' => 'EMP005',
                    'first_name' => 'David',
                    'last_name' => 'Kimaro',
                    'phone' => '0789456123',
                    'email' => 'david@company.com',
                    'date_of_birth' => '1994-07-20',
                    'date_hired' => '2023-09-10',
                    'gender' => 'Male',
                    'basic_salary' => 720000,
                    'status' => 'active',
                    'department_id' => $departments[2]->id ?? 3,   // IT
                    'position_id' => $positions[4]->id ?? 5,
                ]
            ],
        ];

        foreach ($users as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $user->assignRole($data['role']);

            Employee::create(array_merge($data['employee'], [
                'user_id' => $user->id,
            ]));
        }
    }
}