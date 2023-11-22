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
        PermissionModule::firstOrCreate(['name' => 'ticket']);
    }
}
