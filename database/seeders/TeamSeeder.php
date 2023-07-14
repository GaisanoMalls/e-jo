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
                'name' => 'Retail'
            ],
            [
                'id' => 2,
                'name' => 'ICT Support'
            ],
            [
                'id' => 3,
                'name' => 'System Development'
            ],
        ];

        foreach ($teams as $team) {
            $department = Department::where('id', $team['id'])->first();
            if ($department) {
                Team::firstOrCreate([
                    'service_department_id' => 1,
                    'name' => $team['name'],
                    'slug' => \Str::slug($team['name'])
                ]);
            }
        }
    }
}
