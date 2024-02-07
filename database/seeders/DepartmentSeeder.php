<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            'ICT',
            'HR',
            'SPE',
            'SPM',
            'FPM',
            'DPS',
        ];

        foreach ($departments as $department) {
            $department = Department::firstOrCreate([
                'name' => $department,
                'slug' => Str::slug($department),
            ]);
        }
    }
}
