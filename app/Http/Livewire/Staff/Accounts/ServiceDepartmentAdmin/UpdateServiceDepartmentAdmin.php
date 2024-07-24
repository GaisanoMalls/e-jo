<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class UpdateServiceDepartmentAdmin extends Component
{
    use BasicModelQueries, Utils;

    public User $serviceDeptAdmin;
    public array $branches = [];
    public array $service_departments = [];
    public string $first_name;
    public ?string $middle_name = null;
    public string $last_name;
    public string $email;
    public ?string $suffix = null;
    public int $bu_department;
    public array $permissions = [];
    public array $currentPermissions = [];

    public function mount()
    {
        $this->branches = $this->serviceDeptAdmin->branches->pluck('id')->toArray();
        $this->service_departments = $this->serviceDeptAdmin->serviceDepartments->pluck('id')->toArray();
        $this->bu_department = $this->serviceDeptAdmin->buDepartments->pluck('id')->first();
        $this->first_name = $this->serviceDeptAdmin->profile->first_name;
        $this->middle_name = $this->serviceDeptAdmin->profile->middle_name;
        $this->last_name = $this->serviceDeptAdmin->profile->last_name;
        $this->suffix = $this->serviceDeptAdmin->profile->suffix;
        $this->email = $this->serviceDeptAdmin->email;
        $this->currentPermissions = $this->serviceDeptAdmin->getDirectPermissions()->pluck('name')->toArray();
    }

    private function isCostingApprover1()
    {
        return SpecialProjectAmountApproval::where('service_department_admin_approver->approver_id', $this->serviceDeptAdmin->id)->exists();
    }

    public function rules()
    {
        return [
            'branches' => 'required',
            'bu_department' => 'required',
            'service_departments' => 'required',
            'first_name' => 'required|min:2|max:100',
            'middle_name' => 'nullable|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'suffix' => 'nullable|min:1|max:4',
            'email' => "required|max:80|unique:users,email,{$this->serviceDeptAdmin->id}",
        ];
    }

    public function updateServiceDepartmentAdminAccount()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->serviceDeptAdmin->update(['email' => $this->email]);
                $this->serviceDeptAdmin->branches()->sync($this->branches);
                $this->serviceDeptAdmin->buDepartments()->sync([$this->bu_department]);
                $this->serviceDeptAdmin->serviceDepartments()->sync($this->service_departments);
                $this->serviceDeptAdmin->syncPermissions($this->permissions);

                $this->serviceDeptAdmin->profile()->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'slug' => $this->slugify(implode(" ", [
                        $this->first_name,
                        $this->middle_name,
                        $this->last_name,
                        $this->suffix,
                    ])),
                ]);

                noty()->addSuccess("You have successfully updated the account for {$this->serviceDeptAdmin->profile->getFullName}.");
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.service-department-admin.update-service-department-admin', [
            'serviceDeptAdminSuffixes' => $this->querySuffixes(),
            'serviceDeptAdminBranches' => $this->queryBranches(),
            'serviceDeptAdminBUDepartments' => $this->queryBUDepartments(),
            'serviceDeptAdminServiceDepartments' => $this->queryServiceDepartments(),
            'allPermissions' => Permission::withWhereHas('roles', fn($role) => $role->where('roles.name', Role::SERVICE_DEPARTMENT_ADMIN))->get()
        ]);
    }
}
