<?php

namespace App\Http\Livewire\Staff\RolesAndPermissions;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionList extends Component
{
    protected $listeners = ['loadPermissionList' => '$refresh'];

    public function deletePermission(Permission $permission)
    {
        try {
            $permission->delete();
            $this->emit('loadAssignPermissionList');
            noty()->addSuccess('Permission successfully deleted');
        } catch (\Exception $e) {
            noty()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        $permissions = Permission::orderBy('created_at', 'desc')->get();
        return view('livewire.staff.roles-and-permissions.permission-list', [
            'permissions' => $permissions
        ]);
    }
}
