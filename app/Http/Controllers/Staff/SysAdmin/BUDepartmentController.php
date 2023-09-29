<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;

class BUDepartmentController extends Controller
{
    public function __invoke()
    {
        return view('layouts.staff.system_admin.manage.bu_departments.bu_department_index');
    }
}