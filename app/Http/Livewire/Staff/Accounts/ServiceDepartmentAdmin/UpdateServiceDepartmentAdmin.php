<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UpdateServiceDepartmentAdmin extends Component
{
    use BasicModelQueries, Utils;

    public User $serviceDeptAdmin;
    public $BUDepartments = [], $service_departments = [], $currentServiceDepartments = [];
    public $first_name, $middle_name, $last_name, $email, $suffix, $branch, $bu_department;

    public function mount(User $serviceDeptAdmin)
    {
        $this->serviceDeptAdmin = $serviceDeptAdmin;
        $this->first_name = $serviceDeptAdmin->profile->first_name;
        $this->middle_name = $serviceDeptAdmin->profile->middle_name;
        $this->last_name = $serviceDeptAdmin->profile->last_name;
        $this->suffix = $serviceDeptAdmin->profile->suffix;
        $this->email = $serviceDeptAdmin->email;
        $this->branch = $serviceDeptAdmin->branch_id;
        $this->bu_department = $serviceDeptAdmin->department_id;
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->service_departments = $serviceDeptAdmin->serviceDepartments->pluck('id')->toArray();
        $this->currentServiceDepartments = $serviceDeptAdmin->serviceDepartments->pluck('id')->toArray();
    }

    public function updatedBranch()
    {
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->dispatchBrowserEvent('get-branch-bu-departments', ['BUDepartments' => $this->BUDepartments]);
    }

    public function rules()
    {
        return [
            'branch' => 'required',
            'bu_department' => 'required',
            'service_departments' => 'required',
            'first_name' => 'required|min:2|max:100',
            'middle_name' => 'nullable|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'suffix' => 'nullable|min:1|max:4',
            'email' => "required|max:80|unique:users,email,{$this->serviceDeptAdmin->id}"
        ];
    }

    public function updateServiceDepartmentAdminAccount()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->serviceDeptAdmin->update([
                    'branch_id' => $this->branch,
                    'department_id' => $this->bu_department,
                    'email' => $this->email
                ]);

                $this->serviceDeptAdmin->profile()->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'slug' => $this->slugify(implode(" ", [
                        $this->first_name,
                        $this->middle_name,
                        $this->last_name,
                        $this->suffix
                    ]))
                ]);

                $this->serviceDeptAdmin->serviceDepartments()->sync($this->service_departments);
            });

            flash()->addSuccess("You have successfully updated the account for {$this->serviceDeptAdmin->profile->getFullName()}.");

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Failed to update the account.');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.service-department-admin.update-service-department-admin', [
            'serviceDeptAdminSuffixes' => $this->querySuffixes(),
            'serviceDeptAdminBranches' => $this->queryBranches(),
            'serviceDeptAdminBUDepartments' => $this->BUDepartments,
            'serviceDeptAdminServiceDepartments' => $this->queryServiceDepartments(),

        ]);
    }
}
