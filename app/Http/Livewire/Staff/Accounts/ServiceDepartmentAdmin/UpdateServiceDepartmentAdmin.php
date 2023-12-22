<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UpdateServiceDepartmentAdmin extends Component
{
    use BasicModelQueries, Utils;

    public User $serviceDeptAdmin;
    public $checkAsLevel1Approver = false;
    public $branches = [];
    public $BUDepartments = [];
    public $service_departments = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $suffix;
    public $bu_department;

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
        $this->checkAsLevel1Approver = $serviceDeptAdmin->levels->contains(
            fn($level) => $level->pivot->user_id == $serviceDeptAdmin->id
        );
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
                flash()->addSuccess("You have successfully updated the account for {$this->serviceDeptAdmin->profile->getFullName()}.");
            });
        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Failed to update the account.');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.service-department-admin.update-service-department-admin', [
            'serviceDeptAdminSuffixes' => $this->querySuffixes(),
            'serviceDeptAdminBranches' => $this->queryBranches(),
            'serviceDeptAdminBUDepartments' => $this->queryBUDepartments(),
            'serviceDeptAdminServiceDepartments' => $this->queryServiceDepartments(),

        ]);
    }
}
