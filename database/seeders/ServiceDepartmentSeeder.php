<?php

namespace Database\Seeders;

use App\Models\ServiceDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $serviceDepartments = [
            'ICT',
            'HR',
            'SPE',
            'SPM',
            'DPS',
        ];

        foreach ($serviceDepartments as $serviceDepartment) {
            ServiceDepartment::firstOrCreate([
                'name' => $serviceDepartment,
                'slug' => Str::slug($serviceDepartment),
            ]);
        }
    }
}
