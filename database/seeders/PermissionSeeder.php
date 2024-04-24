<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Role as UserRole;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $otherPermissions = ['manage app', 'approve special project', 'approve special project costing'];
        foreach ($otherPermissions as $otherPermission) {
            Permission::firstOrCreate([
                'name' => $otherPermission,
            ]);
        }

        // Permission for ticket
        $ticketPermissions = [
            'create',
            'view',
            'approve',
            'disapprove',
            'forward',
            'assign',
            'close',
            'claim',
        ];
        foreach ($ticketPermissions as $ticketPermission) {
            Permission::firstOrCreate([
                'name' => "{$ticketPermission} ticket"
            ]);
        }

        // Permission for costing
        $costingPermissions = ['set', 'edit'];
        foreach ($costingPermissions as $costing) {
            Permission::firstOrCreate([
                'name' => "{$costing} costing"
            ]);
        }

        // Permission for ticket feedback
        $feedbackPermissions = ['create', 'view', 'delete', 'update'];
        foreach ($feedbackPermissions as $feedbackPermission) {
            Permission::firstOrCreate([
                'name' => "{$feedbackPermission} feedback"
            ]);
        }

        // Assign permission to admin
        $adminRole = Role::where('name', UserRole::SYSTEM_ADMIN)->first();
        $adminRole->givePermissionTo([
            'manage app',
        ]);

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
