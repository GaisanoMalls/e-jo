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
    public ?array $permissions = [];
    public ?string $roleName = null;
    public ?int $assignPermissionRoleId = null;
    public ?int $editPermissionRoleId = null;

    protected $listeners = ['loadAssignPermissionList' => '$refresh'];

    public function rules()
    {
        return (new GivePermissionRequest())->rules();
    }

    public function assignPermissionToRole(Role $role)
    {
        $this->assignPermissionRoleId = $role->id;
        $this->roleName = $role->name;
        $this->dispatchBrowserEvent('refresh-permission-select', ['allPermissions' => $this->getAllPermissions()]);
    }

    public function removePermission(Role $role, Permission $permission)
    {
        try {
            $permission = Permission::find($permission->id);
            if ($permission) {
                $role->revokePermissionTo($permission);
                flash()->addSuccess('Permission revoked successfully');
            } else {
                flash()->addError('Permission not found');
            }
        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function editAssignedPermission(Role $role)
    {
        $this->editPermissionRoleId = $role->id;
        $this->roleName = $role->name;
        $this->dispatchBrowserEvent('get-role-permissions-to-edit', [
            'currentPermissions' => $role->permissions->pluck('name')->toArray()
        ]);
    }

    public function updateAssignedPermission()
    {
        try {
            $role = Role::find($this->editPermissionRoleId);
            // TODO $role->syncPermissions();
        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    private function getAllPermissions()
    {
        return $this->allPermissions = Permission::all();
    }

    private function actionOnSubmit()
    {
        sleep(1);
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
        foreach ($this->permissions as $permission) {
            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
        }

        flash()->addSuccess('Permission assigned.');
        $this->actionOnSubmit();
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.staff.roles-and-permissions.give-permission-list', [
            'roles' => $roles,
            'allPermissions' => $this->getAllPermissions()
        ]);
    }
}
