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
    public $branches = [];
    public $BUDepartments = [];
    public $service_departments = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $suffix;
    public $bu_department;
    public $permissions = [];
    public $currentPermissions = [];
    public $asCostingApprover1 = false;

    public function mount(User $serviceDeptAdmin)
    {
        $this->serviceDeptAdmin = $serviceDeptAdmin;
        $this->branches = $serviceDeptAdmin->branches->pluck('id')->toArray();
        $this->service_departments = $serviceDeptAdmin->serviceDepartments->pluck('id')->toArray();
        $this->bu_department = $serviceDeptAdmin->buDepartments->pluck('id');
        $this->first_name = $serviceDeptAdmin->profile->first_name;
        $this->middle_name = $serviceDeptAdmin->profile->middle_name;
        $this->last_name = $serviceDeptAdmin->profile->last_name;
        $this->suffix = $serviceDeptAdmin->profile->suffix;
        $this->email = $serviceDeptAdmin->email;
        $this->asCostingApprover1 = $this->isCostingApprover1();
        $this->currentPermissions = $serviceDeptAdmin->getDirectPermissions()->pluck('name')->toArray();
    }

    private function isCostingApprover1()
    {
        return SpecialProjectAmountApproval::where('service_department_admin_approver->approver_id', $this->serviceDeptAdmin->id)->exists();
    }

    public function currentUserAsCostingApprover1()
    {
        return SpecialProjectAmountApproval::whereNotNull('service_department_admin_approver')
            ->whereJsonContains('service_department_admin_approver->approver_id', $this->serviceDeptAdmin->id)
            ->exists();
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
                $this->serviceDeptAdmin->buDepartments()->sync($this->bu_department);
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

                if ($this->asCostingApprover1) {
                    if (!$this->hasCostingApprover1()) {
                        if (SpecialProjectAmountApproval::whereNull('service_department_admin_approver')->exists()) {
                            SpecialProjectAmountApproval::whereNull('service_department_admin_approver')
                                ->update([
                                    'service_department_admin_approver' => [
                                        'approver_id' => $this->serviceDeptAdmin->id,
                                        'is_approved' => false,
                                        'date_approved' => null
                                    ]
                                ]);
                        } elseif (SpecialProjectAmountApproval::whereNull('fpm_coo_approver')->exists()) {
                            SpecialProjectAmountApproval::whereNull('fpm_coo_approver')
                                ->update([
                                    'service_department_admin_approver' => [
                                        'approver_id' => $this->serviceDeptAdmin->id,
                                        'is_approved' => false,
                                        'date_approved' => null
                                    ]
                                ]);
                        } else {
                            // If neither field is null, create a new record
                            SpecialProjectAmountApproval::create([
                                'service_department_admin_approver' => [
                                    'approver_id' => $this->serviceDeptAdmin->id,
                                    'is_approved' => false,
                                    'date_approved' => null
                                ]
                            ]);
                        }
                    } else {
                        noty()->warning('Costing approver 1 already assigned');
                    }
                } else {
                    if (SpecialProjectAmountApproval::whereJsonContains('service_department_admin_approver->approver_id', $this->serviceDeptAdmin->id)->exists()) {
                        if ($this->hasCostingApprover2()) {
                            SpecialProjectAmountApproval::whereNotNull('fpm_coo_approver')
                                ->update(['service_department_admin_approver' => null]);
                        }
                        if ($this->hasCostingApprover1() && !$this->hasCostingApprover2()) {
                            SpecialProjectAmountApproval::query()->delete();
                        }
                    }
                }

                noty()->addSuccess("You have successfully updated the account for {$this->serviceDeptAdmin->profile->getFullName()}.");
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
            'currentUserAsCostingApprover1' => $this->currentUserAsCostingApprover1(),
            'hasCostingApprover1' => $this->hasCostingApprover1(),
            'allPermissions' => Permission::withWhereHas('roles', fn($role) => $role->where('roles.name', Role::SERVICE_DEPARTMENT_ADMIN))->get()
        ]);
    }
}
