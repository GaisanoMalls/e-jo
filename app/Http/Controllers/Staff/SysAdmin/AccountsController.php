<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;

class AccountsController extends Controller
{
    public function index()
    {
        return view('layouts.staff.system_admin.manage.accounts.account_main');
    }

    public function approvers()
    {
        return view('layouts.staff.system_admin.manage.accounts.roles.approvers_list');
    }

    public function serviceDepartmentAdmins()
    {
        return view('layouts.staff.system_admin.manage.accounts.roles.service_department_admins_list');
    }

    public function agents()
    {
        return view('layouts.staff.system_admin.manage.accounts.roles.agents_list');
    }

    public function users()
    {
        return view('layouts.staff.system_admin.manage.accounts.roles.users_list');
    }
}