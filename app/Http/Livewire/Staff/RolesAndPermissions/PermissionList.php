<?php

namespace App\Http\Livewire\Staff\RolesAndPermissions;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class PermissionList extends Component
{
    use WithPagination;

    public string $searchPermission = "";
    public array $pageNumberOptions = [5, 10, 20];
    public int $paginatePageNumber = 5;
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

    public function updatingSearchPermission()
    {
        $this->resetPage();
    }

    public function clearSearchPermission()
    {
        $this->searchPermission = '';
    }

    public function render()
    {
        $permissions = Permission::where('name', 'like', '%' . $this->searchPermission . '%')
            ->paginate(perPage: $this->paginatePageNumber);
        return view('livewire.staff.roles-and-permissions.permission-list', [
            'permissions' => $permissions,
        ]);
    }
}
