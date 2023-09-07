<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch_names = ['Bajada', 'Digos'];

        foreach ($branch_names as $branch_name) {
            Branch::firstOrCreate([
                'name' => $branch_name,
                'slug' => \Str::slug($branch_name)
            ]);
        }
    }
}