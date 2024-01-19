<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ServiceDeptAdminList extends Component
{
    public $serviceDepartmentAdmins;
    public $serviceDeptAdminDeleteId;
    public $serviceDeptAdminFullName;

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
            noty()->addSuccess('Service department admin account has been deleted');

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addSuccess('Oops, something went wrong');
        }
    }

    private function getInitialQuery()
    {
        return User::role(Role::SERVICE_DEPARTMENT_ADMIN)->orderByDesc('created_at')->get();
    }

    public function render()
    {
        $this->serviceDepartmentAdmins = (Route::is('staff.manage.user_account.index'))
            ? User::with(['profile'])->role(Role::SERVICE_DEPARTMENT_ADMIN)
                ->take(5)->orderByDesc('created_at')->get()
            : (
                (Route::is('staff.manage.user_account.service_department_admins'))
                ? User::with(['profile'])->role(Role::SERVICE_DEPARTMENT_ADMIN)
                    ->orderByDesc('created_at')->get()
                : $this->getInitialQuery()
            );

        return view('livewire.staff.accounts.service-department-admin.service-dept-admin-list', [
            'serviceDepartmentAdmins' => $this->serviceDepartmentAdmins,
        ]);
    }
}
