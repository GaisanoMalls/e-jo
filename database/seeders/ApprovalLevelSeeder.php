<?php

namespace Database\Seeders;

use App\Models\ApprovalLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApprovalLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
            ['value' => 1, 'description' => '1 Level'],
            ['value' => 2, 'description' => '2 Levels'],
            ['value' => 3, 'description' => '3 Levels'],
            ['value' => 4, 'description' => '4 Levels'],
            ['value' => 5, 'description' => '5 Levels']
        ];

        foreach ($levels as $level) {
            ApprovalLevel::firstOrCreate([
                'value' => $level['value'],
                'description' => $level['description']
            ]);
        }
    }
}
