<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Department;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();

        $positions = [
            // HR Department
            ['department_id' => $departments[0]->id ?? 1, 'name' => 'HR Manager', 'level' => 'Senior'],
            ['department_id' => $departments[0]->id ?? 1, 'name' => 'HR Officer', 'level' => 'Junior'],
            
            // Finance
            ['department_id' => $departments[1]->id ?? 2, 'name' => 'Finance Manager', 'level' => 'Senior'],
            ['department_id' => $departments[1]->id ?? 2, 'name' => 'Accountant', 'level' => 'Mid'],
            
            // IT
            ['department_id' => $departments[2]->id ?? 3, 'name' => 'IT Manager', 'level' => 'Senior'],
            ['department_id' => $departments[2]->id ?? 3, 'name' => 'Software Developer', 'level' => 'Mid'],
            
            // Sales
            ['department_id' => $departments[3]->id ?? 4, 'name' => 'Sales Manager', 'level' => 'Senior'],
            ['department_id' => $departments[3]->id ?? 4, 'name' => 'Sales Executive', 'level' => 'Junior'],
        ];

        foreach ($positions as $pos) {
            Position::create($pos);
        }
    }
}