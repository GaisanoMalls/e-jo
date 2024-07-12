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
use Livewire\WithPagination;

class ServiceDeptAdminList extends Component
{
    use Utils, WithPagination;

    public ?int $serviceDeptAdminDeleteId = null;
    public ?string $serviceDeptAdminFullName = null;
    public ?string $searchServiceDeptAdmin = null;

    protected $paginationTheme = 'bootstrap';
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
        return User::whereHas('profile', function ($profile) {
            $profile->where('first_name', 'like', '%' . $this->searchServiceDeptAdmin . '%')
                ->orWhere('middle_name', 'like', '%' . $this->searchServiceDeptAdmin . '%')
                ->orWhere('last_name', 'like', '%' . $this->searchServiceDeptAdmin . '%');
        })
            ->orWhereHas('serviceDepartments', fn($serviceDept) => $serviceDept->where('name', 'like', '%' . $this->searchServiceDeptAdmin . '%'))
            ->orWhereHas('branches', fn($branch) => $branch->where('name', 'like', '%' . $this->searchServiceDeptAdmin . '%'))
            ->orWhereHas('buDepartments', fn($buDept) => $buDept->where('name', 'like', '%' . $this->searchServiceDeptAdmin . '%'))
            ->role(Role::SERVICE_DEPARTMENT_ADMIN)
            ->orderByDesc('created_at')
            ->paginate(25);
    }

    public function updatingSearchServiceDeptAdmin()
    {
        $this->resetPage();
    }

    public function clearServiceDeptAdminSearch()
    {
        $this->searchServiceDeptAdmin = '';
    }

    public function render()
    {
        $serviceDepartments = $this->getInitialQuery();

        if (Route::is('staff.manage.user_account.index')) {
            $serviceDepartments = User::with('profile')
                ->role(Role::SERVICE_DEPARTMENT_ADMIN)
                ->orderByDesc('created_at')
                ->paginate(15);
        }

        if (Route::is('staff.manage.user_account.service_department_admins')) {
            $serviceDepartments = $this->getInitialQuery();
        }
        return view('livewire.staff.accounts.service-department-admin.service-dept-admin-list', [
            'serviceDepartmentAdmins' => $serviceDepartments
        ]);
    }
}
