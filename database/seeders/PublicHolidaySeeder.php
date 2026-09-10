<?php

namespace Database\Seeders;

use App\Models\PublicHoliday;
use Illuminate\Database\Seeder;

class PublicHolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            ['name' => 'New Year\'s Day',       'date' => '2026-01-01', 'is_recurring' => true],
            ['name' => 'Mapinduzi Day', 'date' => '2026-01-12', 'is_recurring' => true],
            ['name' => 'Karume Day',            'date' => '2026-04-07', 'is_recurring' => true],
            ['name' => 'Union Day',             'date' => '2026-04-26', 'is_recurring' => true],
            ['name' => 'Labour Day',            'date' => '2026-05-01', 'is_recurring' => true],
            ['name' => 'Saba Saba Day',         'date' => '2026-07-07', 'is_recurring' => true],
            ['name' => 'Nane Nane Day',         'date' => '2026-08-08', 'is_recurring' => true],
            ['name' => 'Nyerere Day',           'date' => '2026-10-14', 'is_recurring' => true],
            ['name' => 'Independence Day',      'date' => '2026-12-09', 'is_recurring' => true],
            ['name' => 'Christmas Day',         'date' => '2026-12-25', 'is_recurring' => true],
            ['name' => 'Boxing Day',            'date' => '2026-12-26', 'is_recurring' => true],
        ];

        foreach ($holidays as $holiday) {
            PublicHoliday::updateOrCreate(
                ['date' => $holiday['date']],
                $holiday
            );
        }
    }
}
