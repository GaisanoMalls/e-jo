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
                'id' => 2,
                'name' => 'ICT Support'
            ],
            [
                'id' => 3,
                'name' => 'System Development'
            ],
            [
                'id' => 5,
                'name' => 'Sales Audit'
            ],
        ];

        foreach ($teams as $team) {
            Team::firstOrCreate([
                'name' => $team['name'],
                'slug' => \Str::slug($team['name'])
            ]);
        }
    }
}