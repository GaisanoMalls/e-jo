<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ServiceDeptAdminList extends Component
{
    use Utils;

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
            User::where('id', $this->serviceDeptAdminDeleteId)->delete();
            if ($this->hasCostingApprover2()) {
                SpecialProjectAmountApproval::whereNotNull('fpm_coo_approver')
                    ->update(['service_department_admin_approver' => null]);
            }
            if ($this->hasCostingApprover1() && !$this->hasCostingApprover2()) {
                SpecialProjectAmountApproval::whereJsonContains('service_department_admin_approver->approver_id', $this->serviceDeptAdminDeleteId)->delete();
            }

            $this->serviceDeptAdminDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('Service department admin account has been deleted');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
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
