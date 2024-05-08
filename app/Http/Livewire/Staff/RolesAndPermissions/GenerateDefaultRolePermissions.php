<?php

namespace App\Http\Livewire\Staff\RolesAndPermissions;

use Database\Seeders\DirectPermissionSeeder;
use Livewire\Component;

class GenerateDefaultRolePermissions extends Component
{
    public function generateDirectPermissions()
    {
        sleep(2);
        $directPermissions = new DirectPermissionSeeder;
        $directPermissions->run();
        $this->emit('loadAssignPermissionList');
        noty()->addSuccess('Default permissions have been successfully assigned to all users');
    }

    public function render()
    {
        return view('livewire.staff.roles-and-permissions.generate-default-role-permissions');
    }
}
