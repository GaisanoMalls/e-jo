<?php

namespace App\Http\Livewire\Staff\RolesAndPermissions;

use App\Http\Requests\SysAdmin\Manage\Permission\GivePermissionRequest;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Role as UserRole;

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
            'allPermissions' => Permission::withWhereHas('roles', fn($query) => $query->where('roles.name', $role->name))->get(['name'])->toArray(),
            'currentPermissions' => $role->permissions->pluck('name')->toArray(),
        ]);
    }

    public function resetPermissionsByRole(string $roleName)
    {
        switch ($roleName) {
            case UserRole::SERVICE_DEPARTMENT_ADMIN:
                $serviceDepartmentAdminRole = Role::where('name', UserRole::SERVICE_DEPARTMENT_ADMIN)->first();
                $serviceDepartmentAdminRole->syncPermissions([
                    'forward ticket',
                    'assign ticket',
                    'approve ticket',
                    'approve special project costing',
                    'request ticket approval'
                ]);
                break;

            case UserRole::APPROVER:
                $approverRole = Role::where('name', UserRole::APPROVER)->first();
                $approverRole->syncPermissions([
                    'view ticket',
                    'approve special project',
                    'approve special project costing',
                    'disapprove special project costing'
                ]);
                break;

            case UserRole::AGENT:
                $agentRole = Role::where('name', UserRole::AGENT)->first();
                $agentRole->syncPermissions([
                    'view ticket',
                    'claim ticket',
                    'close ticket',
                    'set costing',
                    'edit costing'
                ]);
                break;

            case UserRole::USER:
                $requesterRole = Role::where('name', UserRole::USER)->first();
                $requesterRole->syncPermissions([
                    'create ticket',
                    'create feedback',
                    'view feedback',
                    'edit feedback',
                    'delete feedback'
                ]);
                break;

            default:
                noty()->addError('Undefined role name');
        }

        $this->emit('loadAssignPermissionList');
    }

    public function render()
    {
        $roles = Role::whereNot('name', UserRole::SYSTEM_ADMIN)->get();
        return view('livewire.staff.roles-and-permissions.give-permission-list', [
            'roles' => $roles,
            'allPermissions' => $this->getAllPermissions(),
        ]);
    }
}
