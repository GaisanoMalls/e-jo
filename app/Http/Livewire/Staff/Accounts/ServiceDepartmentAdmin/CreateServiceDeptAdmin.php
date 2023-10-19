<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Http\Requests\SysAdmin\Manage\Account\StoreServiceDeptAdminRequest;
use Livewire\Component;

class CreateServiceDeptAdmin extends Component
{
    public $BUDepartments = [], $selectedServiceDepartments = [];
    public $first_name, $middle_name, $last_name, $email, $suffix, $branch, $bu_department;

    public function rules()
    {
        return (new StoreServiceDeptAdminRequest())->rules();
    }

    public funcruon

    public function render()
    {
        return view('livewire.staff.accounts.service-department-admin.create-service-dept-admin');
    }
}
