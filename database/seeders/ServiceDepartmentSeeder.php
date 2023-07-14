<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\ServiceDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            [
                'id' => 1,
                'name' => 'Maintenance'
            ],
            [
                'id' => 2,
                'name' => 'Facilities'
            ],
            [
                'id' => 3,
                'name' => 'ICT'
            ],
        ];

        foreach ($serviceDepartments as $serviceDepartment) {
            ServiceDepartment::firstOrCreate([
                'department_id' => Department::pluck('id')->first(),
                'name' => $serviceDepartment['name'],
                'slug' => \Str::slug($serviceDepartment['name'])
            ]);
        }
    }
}
