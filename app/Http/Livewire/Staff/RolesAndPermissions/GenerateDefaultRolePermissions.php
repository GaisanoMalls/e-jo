<?php

namespace App\Http\Livewire\Staff\RolesAndPermissions;

use Database\Seeders\PermissionViaRolesSeeder;
use Livewire\Component;

class GenerateDefaultRolePermissions extends Component
{
    public function generateDefaultRolePermissions()
    {
        sleep(2);
        $permissionRoles = new PermissionViaRolesSeeder;
        $permissionRoles->run();
        $this->emit('loadAssignPermissionList');
        noty()->addSuccess('Default role permissions have been successfully generated');
    }

    public function render()
    {
        return view('livewire.staff.roles-and-permissions.generate-default-role-permissions');
    }
}
