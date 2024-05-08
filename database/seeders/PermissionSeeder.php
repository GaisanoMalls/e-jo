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
        $otherPermissions = ['manage app', 'approve special project', 'approve special project costing', 'disapprove special project costing'];
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
        $feedbackPermissions = ['create', 'edit', 'view', 'delete', 'update'];
        foreach ($feedbackPermissions as $feedbackPermission) {
            Permission::firstOrCreate([
                'name' => "{$feedbackPermission} feedback"
            ]);
        }
    }
}
