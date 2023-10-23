<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AccountUserController extends Controller
{
    public function viewDetails(User $user)
    {
        return view('layouts.staff.system_admin.manage.accounts.roles.details.user_details', compact('user'));
    }

    public function editDetails(User $user)
    {
        return view('layouts.staff.system_admin.manage.accounts.edit.edit_user', compact('user'));
    }
}