<?php

namespace App\Http\Livewire\Staff\RolesAndPermissions;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class PermissionList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['loadPermissionList' => '$refresh'];

    public function deletePermission(Permission $permission)
    {
        try {
            $permission->delete();
            noty()->addSuccess('Permission successfully deleted');
        } catch (\Exception $e) {
            noty()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        $permissions = Permission::orderBy('created_at', 'desc')->paginate(5);
        return view('livewire.staff.roles-and-permissions.permission-list', [
            'permissions' => $permissions
        ]);
    }
}
