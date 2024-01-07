<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Http\Requests\SysAdmin\Manage\Account\StoreServiceDeptAdminRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\ApproverLevel;
use App\Models\Department;
use App\Models\Level;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateServiceDeptAdmin extends Component
{
    use BasicModelQueries, Utils;

    public $branches = [];
    public $BUDepartments = [];
    public $service_departments = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $suffix;
    public $bu_department;

    public function rules()
    {
        return (new StoreServiceDeptAdminRequest())->rules();
    }

    public function actionOnSubmit()
    {
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
                    'email' => $this->email,
                    'password' => \Hash::make('deptadmin'),
                ]);

                $serviceDeptAdmin->assignRole(Role::SERVICE_DEPARTMENT_ADMIN);
                $serviceDeptAdmin->buDepartments()->attach($this->bu_department);
                $serviceDeptAdmin->branches()->attach(array_map('intval', $this->branches));
                $serviceDeptAdmin->serviceDepartments()->attach($this->service_departments);

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
                        $this->suffix,
                    ])),
                ]);

                ApproverLevel::create([
                    'user_id' => $serviceDeptAdmin->id,
                    'level_id' => Level::where('value', 1)->pluck('value')->first(),
                ]);

                $this->actionOnSubmit();
                noty()->addSuccess('Account successfully created');
            });
        } catch (Exception $e) {
            dump($e->getMessage());
            noty()->addSuccess('Failed to save a new service department admin');
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
            'buDepartments' => $this->queryBUDepartments(),
            'serviceDeptAdminServiceDepartments' => $this->queryServiceDepartments(),
        ]);
    }
}
