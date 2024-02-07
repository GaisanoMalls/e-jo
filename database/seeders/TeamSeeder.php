<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\ServiceDepartment;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            'Product Maint.',
            'Price Mgmt.',
            'POS',
            'MMS',
            'Inventory',
            'Concessionaire',
            'Electrical',
            'Merchandise',
            'Plumbing',
            'RAC',
            'ICT Support',
            'System Development',
            'Carpentry',
            'Tiling',
            'Painting',
            'Welding',
            'Maintenance SU',
            'Labor',
            'Elevator & Escalators',
            'DTR'
        ];

        foreach ($teams as $team) {
            Team::firstOrCreate([
                'name' => $team,
                'slug' => Str::slug($team),
            ]);
        }
    }
}