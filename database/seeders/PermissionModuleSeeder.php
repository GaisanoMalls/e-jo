<?php

namespace Database\Seeders;

use App\Models\PermissionModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionModules = [
            'accounts',
            'service level agreements',
            'branches',
            'bu/departments',
            'service departments',
            'teams',
            'help topics',
            'tags',
            'announcements',
            'ticket'
        ];

        foreach ($permissionModules as $module) {
            PermissionModule::firstOrCreate([
                'name' => $module
            ]);
        }

    }
}
