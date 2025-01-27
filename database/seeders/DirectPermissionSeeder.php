<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DirectPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assign direct permissions to service department admins
        $serviceDeptAdmins = User::withWhereHas('roles', function ($role) {
            $role->where('roles.name', Role::SERVICE_DEPARTMENT_ADMIN);
        })->get();

        $serviceDeptAdmins->each(function ($serviceDeptAdmin) {
            $serviceDeptAdmin->syncPermissions([
                'forward ticket',
                'assign ticket',
                'approve ticket',
                'approve special project costing',
                'request ticket approval'
            ]);
        });

        // Assign direct permissions to agents
        $agents = User::withWhereHas('roles', function ($role) {
            $role->where('roles.name', Role::AGENT);
        })->get();

        $agents->each(function ($agent) {
            $agent->syncPermissions([
                'view ticket',
                'claim ticket',
                'close ticket',
                'set costing',
                'edit costing'
            ]);
        });

        // Assign direct permissions to approvers
        $approvers = User::withWhereHas('roles', function ($role) {
            $role->where('roles.name', Role::APPROVER);
        })->get();

        $approvers->each(function ($approver) {
            $approver->syncPermissions([
                'view ticket',
                'approve special project',
                'approve special project costing'
            ]);
        });

        // Assign direct permissions to requesters
        $requesters = User::withWhereHas('roles', function ($role) {
            $role->where('roles.name', Role::USER);
        })->get();

        $requesters->each(function ($requester) {
            $requester->syncPermissions([
                'create ticket',
                'create feedback',
                'view feedback',
                'edit feedback',
                'delete feedback'
            ]);
        });
    }
}
