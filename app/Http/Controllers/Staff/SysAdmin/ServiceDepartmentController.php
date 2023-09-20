<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;

class ServiceDepartmentController extends Controller
{
    public function __invoke()
    {
        return view('layouts.staff.system_admin.manage.service_departments.service_department_index');
    }
}