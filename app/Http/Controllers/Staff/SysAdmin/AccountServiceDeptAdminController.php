<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;


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