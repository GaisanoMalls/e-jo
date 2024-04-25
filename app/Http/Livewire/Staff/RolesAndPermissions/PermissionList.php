<?php

namespace App\Http\Livewire\Staff\RolesAndPermissions;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class PermissionList extends Component
{
    use WithPagination;

    public $search = '';
    public $numberList = [5, 10, 20];
    public $paginatePageNumber = 5;

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

    public function updatingSearch()
    {
        $this->resetPage(pageName: 'p');
    }

    public function render()
    {
        $permissions = Permission::where('name', 'like', '%' . $this->search . '%')->paginate(perPage: $this->paginatePageNumber, pageName: 'p');
        return view('livewire.staff.roles-and-permissions.permission-list', [
            'permissions' => $permissions,
        ]);
    }
}
