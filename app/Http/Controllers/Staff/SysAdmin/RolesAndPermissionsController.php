<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RolesAndPermissionsController extends Controller
{
    public function index()
    {
        $users = User::with(['department', 'branch', 'role'])->orderBy('created_at', 'asc')->get();
        return view('layouts.staff.system_admin.manage.roles_and_permissions.roles_and_permissions_index',
            compact([
                'users'
            ])
        );
    }

    public function filter()
    {
        //
    }
}
