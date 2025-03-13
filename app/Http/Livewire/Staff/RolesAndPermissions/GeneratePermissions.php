<?php

namespace App\Http\Livewire\Staff\RolesAndPermissions;

use Database\Seeders\PermissionSeeder;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class GeneratePermissions extends Component
{
    public function generatePermissions()
    {
        sleep(2);
        $permission = new PermissionSeeder;
        $permission->run();
        $this->emit('loadPermissionList');
        noty()->addSuccess('Ticket permissions have been successfully generated.');
    }

    public function render()
    {
        return view('livewire.staff.roles-and-permissions.generate-permissions');
    }
}
