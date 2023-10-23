<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class ServiceDeptAdminList extends Component
{
    public $serviceDeptAdminDeleteId, $serviceDeptAdminFullName;

    protected $listeners = ['loadServiceDeptAdminList' => '$refresh'];

    public function deleteServiceDepartmentAdmin(User $serviceDepartmentAdmin)
    {
        $this->serviceDeptAdminDeleteId = $serviceDepartmentAdmin->id;
        $this->serviceDeptAdminFullName = $serviceDepartmentAdmin->profile->getFullName();
        $this->dispatchBrowserEvent('show-delete-service-dept-admin-modal');
    }

    public function delete()
    {
        try {
            User::find($this->serviceDeptAdminDeleteId)->delete();
            $this->serviceDeptAdminDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Service department admin account has been deleted');
            sleep(1);

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addSuccess('Oops, something went wrong');
        }
    }

    public function render()
    {
        $serviceDepartmentAdmins = User::with(['department', 'branch'])
            ->whereHas('role', fn($agent) => $agent->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN))
            ->take(5)->orderBy('created_at', 'desc')->get();

        return view('livewire.staff.accounts.service-department-admin.service-dept-admin-list', [
            'serviceDepartmentAdmins' => $serviceDepartmentAdmins
        ]);
    }
}
