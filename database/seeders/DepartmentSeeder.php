<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

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
            'DPS',
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate([
                'name' => $department,
                'slug' => \Str::slug($department),
            ]);
        }
    }
}