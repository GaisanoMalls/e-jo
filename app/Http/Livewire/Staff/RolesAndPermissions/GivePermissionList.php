<?php

namespace App\Http\Livewire\Staff\RolesAndPermissions;

use App\Http\Requests\SysAdmin\Manage\Permission\GivePermissionRequest;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GivePermissionList extends Component
{
    public $allPermissions;
    public $currentPermissions;
    public $permissions = [];
    public $roleName;
    public $assignPermissionRoleId;
    public $editPermissionRoleId;

    protected $listeners = ['loadAssignPermissionList' => '$refresh'];

    public function rules()
    {
        return (new GivePermissionRequest())->rules();
    }

    private function getAllPermissions()
    {
        return $this->allPermissions = Permission::all();
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function cancel()
    {
        $this->actionOnSubmit();
    }

    public function givePermission()
    {
        $this->validate();

        $role = Role::find($this->assignPermissionRoleId);
        if (!empty($this->permissions)) {
            $role->syncPermissions($this->permissions);
            noty()->addSuccess('Permission assigned.');
        }
        $this->actionOnSubmit();
    }

    public function assignPermissionToRole(Role $role)
    {
        $this->assignPermissionRoleId = $role->id;
        $this->roleName = $role->name;
        $this->dispatchBrowserEvent('refresh-permission-select', [
            'allPermissions' => $this->getAllPermissions(),
            'currentPermissions' => $role->permissions->pluck('name')->toArray(),
        ]);
    }

    public function removePermission(Role $role, Permission $permission)
    {
        try {
            $permission = Permission::find($permission->id);
            if ($permission) {
                $role->revokePermissionTo($permission);
                noty()->addInfo('Permission revoked.');
            } else {
                noty()->addError('Permission not found');
            }
        } catch (\Exception $e) {
            noty()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.staff.roles-and-permissions.give-permission-list', [
            'roles' => $roles,
            'allPermissions' => $this->getAllPermissions(),
        ]);
    }
}
