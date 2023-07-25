<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => 'Open', 'color' => '#3F993F'],
            ['name' => 'Viewed', 'color' => '#1F75CC'],
            ['name' => 'Approved', 'color' => '#BD7332'],
            ['name' => 'Disapproved', 'color' => '#FF0000'],
            ['name' => 'Claimed', 'color' => '#FF8B8B'],
            ['name' => 'On Process', 'color' => '#1E1C1D'],
            ['name' => 'Overdue', 'color' => '#FD6852'],
            ['name' => 'Closed', 'color' => '#7A7E87'],
        ];

        foreach ($statuses as $status) {
            Status::firstOrCreate([
                'name' => $status['name'],
                'color' => $status['color'],
                'slug' => \Str::slug($status['name'])
            ]);
        }
    }
}