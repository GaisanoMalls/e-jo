<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Role as UserRole;

class PermissionViaRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assign permission to service department admin
        $serviceDepartmentAdminRole = Role::where('name', UserRole::SERVICE_DEPARTMENT_ADMIN)->first();
        $serviceDepartmentAdminRole->givePermissionTo([
            'forward ticket',
            'assign ticket',
            'approve ticket',
            'approve special project costing'
        ]);

        // Assign permission to agent
        $agentRole = Role::where('name', UserRole::AGENT)->first();
        $agentRole->givePermissionTo([
            'view ticket',
            'claim ticket',
            'close ticket',
            'set costing',
            'edit costing'
        ]);

        // Assign permission ot approver
        $approverRole = Role::where('name', UserRole::APPROVER)->first();
        $approverRole->givePermissionTo([
            'approve special project',
            'approve special project costing'
        ]);

        // Assign permission to Requester
        $requesterRole = Role::where('name', UserRole::USER)->first();
        $requesterRole->givePermissionTo([
            'create ticket',
            'create feedback'
        ]);
    }
}
