<?php

namespace Database\Seeders;

use App\Models\PriorityLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            [
                'value' => 1,
                'name' => 'Low',
                'color' => '#5A5A5A',
            ],
            [
                'value' => 2,
                'name' => 'Medium',
                'color' => '#FFA500',
            ],
            [
                'value' => 3,
                'name' => 'High',
                'color' => '#1E4620',
            ],
            [
                'value' => 4,
                'name' => 'Urgent',
                'color' => '#FF0000',
            ],
        ];

        foreach ($levels as $level) {
            PriorityLevel::firstOrCreate([
                'value' => $level['value'],
                'name' => $level['name'],
                'color' => $level['color'],
                'slug' => Str::slug($level['name']),
            ]);
        }
    }
}