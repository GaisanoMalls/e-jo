<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\User;

class AccountAgentController extends Controller
{
    use Utils, BasicModelQueries;


    public function viewDetails(User $agent)
    {
        return view('layouts.staff.system_admin.manage.accounts.roles.details.agent_details', compact('agent'));
    }

    public function editDetails(User $agent)
    {
        return view('layouts.staff.system_admin.manage.accounts.edit.edit_agent', compact('agent'));
    }
}