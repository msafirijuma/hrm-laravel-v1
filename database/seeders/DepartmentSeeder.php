<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Idara ya Rasilimali Watu'],
            ['name' => 'Finance & Accounts', 'code' => 'FIN', 'description' => 'Idara ya Fedha na Uhasibu'],
            ['name' => 'Information Technology', 'code' => 'IT', 'description' => 'Idara ya Teknolojia ya Habari'],
            ['name' => 'Sales & Marketing', 'code' => 'SALES', 'description' => 'Idara ya Uuzaji na Masoko'],
            ['name' => 'Operations', 'code' => 'OPS', 'description' => 'Idara ya Uendeshaji'],
            ['name' => 'Procurement', 'code' => 'PROC', 'description' => 'Idara ya Ununuzi'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}