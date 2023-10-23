<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Account\StoreAgenRequest;
use App\Http\Requests\SysAdmin\Manage\Account\UpdateAgenRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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