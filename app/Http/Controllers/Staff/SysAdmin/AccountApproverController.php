<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AccountApproverController extends Controller
{
    public function viewDetails(User $approver)
    {
        return view('layouts.staff.system_admin.manage.accounts.roles.details.approver_details', compact('approver'));
    }

    public function editDetails(User $approver)
    {
        return view('layouts.staff.system_admin.manage.accounts.edit.edit_approver', compact('approver'));
    }
}