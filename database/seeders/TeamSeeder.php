<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\ServiceDepartment;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            [
                'id' => 1,
                'name' => 'Help Desk'
            ],
            [
                'id' => 2,
                'name' => 'ICT Support'
            ],
            [
                'id' => 3,
                'name' => 'System Development'
            ],
            [
                'id' => 4,
                'name' => 'Price Mngt.'
            ],
            [
                'id' => 5,
                'name' => 'Sales Audit'
            ],
            [
                'id' => 6,
                'name' => 'Inventory and Adjustment'
            ],
            [
                'id' => 7,
                'name' => 'Rewards'
            ],
            [
                'id' => 8,
                'name' => 'Pricing'
            ],
        ];

        foreach ($teams as $team) {
            // $department = Department::where('id', $team['id'])->first();
            // if ($department) {
                Team::firstOrCreate([
                    // 'service_department_id' => 1,
                    'name' => $team['name'],
                    'slug' => \Str::slug($team['name'])
                ]);
            // }
        }
    }
}
