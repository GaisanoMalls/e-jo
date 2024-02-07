<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\SpecialProjectAmountApproval;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

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
        $this->asCostingApprover1 = SpecialProjectAmountApproval::where('service_department_admin_approver->approver_id', $this->serviceDeptAdmin->id)->exists();
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
                    $hasSpecialProjectAmountApproval = SpecialProjectAmountApproval::where('service_department_admin_approver->approver_id', $this->serviceDeptAdmin->id)->first();
                    $hasNoSecialProjectAmountApproval = SpecialProjectAmountApproval::whereNull('service_department_admin_approver->approver_id')->first();

                    if ($hasSpecialProjectAmountApproval) {
                        $hasSpecialProjectAmountApproval->update([
                            'service_department_admin_approver->is_approved' => false,
                            'service_department_admin_approver->date_approved' => null
                        ]);
                    }
                    if ($hasNoSecialProjectAmountApproval) {
                        $hasNoSecialProjectAmountApproval->create([
                            'service_department_admin_approver' => [
                                'approver_id' => $this->serviceDeptAdmin->id,
                                'is_approved' => false,
                                'date_approved' => null
                            ]
                        ]);
                    }
                }

                noty()->addSuccess("You have successfully updated the account for {$this->serviceDeptAdmin->profile->getFullName()}.");
            });
        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Failed to update the account.');
        }
    }

    public function currentUserAsCostingApprover1()
    {
        return SpecialProjectAmountApproval::whereJsonContains('service_department_admin_approver->approver_id', $this->serviceDeptAdmin->id)->first();
    }

    public function render()
    {
        // dd($this->currentUserAsCostingApprover1());
        return view('livewire.staff.accounts.service-department-admin.update-service-department-admin', [
            'serviceDeptAdminSuffixes' => $this->querySuffixes(),
            'serviceDeptAdminBranches' => $this->queryBranches(),
            'serviceDeptAdminBUDepartments' => $this->queryBUDepartments(),
            'serviceDeptAdminServiceDepartments' => $this->queryServiceDepartments(),
            'currentUserAsCostingApprover1' => $this->currentUserAsCostingApprover1()
        ]);
    }
}
