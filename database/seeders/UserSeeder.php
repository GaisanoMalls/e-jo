<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'branch_id' => 1,
            // 'department_id' => 1,
            // 'team_id' => 1,
            'role_id' => 1,
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
        ]);
    }
}