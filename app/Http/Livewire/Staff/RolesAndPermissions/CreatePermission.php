<?php

namespace App\Http\Livewire\Staff\RolesAndPermissions;

use App\Http\Requests\SysAdmin\Manage\Permission\StorePermissionRequest;
use App\Models\PermissionAction;
use App\Models\PermissionModule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class CreatePermission extends Component
{
    public $permissionAction;
    public $permissionModules = [];

    public function rules()
    {
        return (new StorePermissionRequest())->rules();
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('loadPermissionList');
        $this->dispatchBrowserEvent('clear-select-options');
    }

    public function savePermission()
    {
        $this->validate();

        if (!is_null($this->permissionAction) && !empty($this->permissionModules)) {
            foreach ($this->permissionModules as $module) {
                $permissionName = "$this->permissionAction $module";

                if (!Permission::where('name', $permissionName)->exists()) {
                    Permission::create(['name' => $permissionName]);
                } else {
                    noty()->addError('Permission name "' . $permissionName . '" already exists');
                }
            }
        }

        $this->actionOnSubmit();
    }

    public function render()
    {
        return view('livewire.staff.roles-and-permissions.create-permission', [
            'modules' => PermissionModule::orderByDesc('created_at')->get(),
            'actions' => PermissionAction::orderByDesc('created_at')->get()
        ]);
    }
}
