<?php

namespace Database\Seeders;

use App\Models\Suffix;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuffixesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suffixes = [
            Suffix::JR,
            Suffix::SR,
            Suffix::III,
            Suffix::IV,
            Suffix::V
        ];

        foreach ($suffixes as $suffix) {
            Suffix::firstOrCreate(['name' => $suffix]);
        }
    }
}