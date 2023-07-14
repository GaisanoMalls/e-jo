<?php

namespace Database\Seeders;

use App\Models\PriorityLevel;
use Illuminate\Database\Seeder;

class PriorityLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
            ['name' => 'Low', 'color' => '#5A5A5A'],
            ['name' => 'Medium', 'color' => '#FFA500'],
            ['name' => 'High', 'color' => '#1E4620'],
            ['name' => 'Urgent', 'color' => '#FF0000'],
        ];

        foreach ($levels as $level) {
            PriorityLevel::firstOrCreate([
                'name' => $level['name'],
                'color' => $level['color'],
                'slug' => \Str::slug($level['name'])
            ]);
        }
    }
}