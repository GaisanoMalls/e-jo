<?php

namespace Database\Seeders;

use App\Models\Role as ModelRole;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ModelRole::SYSTEM_ADMIN,
            ModelRole::SERVICE_DEPARTMENT_ADMIN,
            ModelRole::APPROVER,
            ModelRole::AGENT,
            ModelRole::USER,
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }
    }
}
