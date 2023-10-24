<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Account\StoreServiceDeptAdminRequest;
use App\Http\Requests\SysAdmin\Manage\Account\UpdateServiceDeptAdminRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AccountServiceDeptAdminController extends Controller
{
    public function viewDetails(User $serviceDeptAdmin)
    {
        return view('layouts.staff.system_admin.manage.accounts.roles.details.service_department_admin_details', compact('serviceDeptAdmin'));
    }

    public function editDetails(User $serviceDeptAdmin)
    {
        return view('layouts.staff.system_admin.manage.accounts.edit.edit_service_dept_admin', compact('serviceDeptAdmin'));
    }
}