<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function __invoke()
    {
        return view('layouts.staff.system_admin.manage.branches.branch_index');
    }
}