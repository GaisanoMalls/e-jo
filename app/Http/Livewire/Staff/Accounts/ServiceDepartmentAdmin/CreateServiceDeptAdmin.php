<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Http\Requests\SysAdmin\Manage\Account\StoreServiceDeptAdminRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateServiceDeptAdmin extends Component
{
    use BasicModelQueries, Utils;

    public $BUDepartments = [], $service_departments = [];
    public $first_name, $middle_name, $last_name, $email, $suffix, $branch, $bu_department;

    public function rules()
    {
        return (new StoreServiceDeptAdminRequest())->rules();
    }

    public function updatedBranch()
    {
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->dispatchBrowserEvent('get-branch-bu-departments', ['BUDepartments' => $this->BUDepartments]);
    }

    public function actionOnSubmit()
    {
        sleep(1);
        $this->reset();
        $this->resetValidation();
        $this->emit('loadServiceDeptAdminList');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function saveServiceDepartmentAdmin()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $serviceDeptAdmin = User::create([
                    'branch_id' => $this->branch,
                    'department_id' => $this->bu_department,
                    'role_id' => Role::SERVICE_DEPARTMENT_ADMIN,
                    'email' => $this->email,
                    'password' => \Hash::make('departmentadmin')
                ]);

                Profile::create([
                    'user_id' => $serviceDeptAdmin->id,
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

                $serviceDeptAdmin->serviceDepartments()->attach($this->service_departments);
            });

            $this->actionOnSubmit();
            flash()->addSuccess('Account successfully created');

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addSuccess('Failed to save a new service department admin');
        }
    }

    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        return view('livewire.staff.accounts.service-department-admin.create-service-dept-admin', [
            'serviceDeptAdminSuffixes' => $this->querySuffixes(),
            'serviceDeptAdminBranches' => $this->queryBranches(),
            'serviceDeptAdminServiceDepartments' => $this->queryServiceDepartments(),
        ]);
    }
}
