<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\ServiceDepartment;
use App\Models\Team;

class TeamController extends Controller
{
    use Utils;

    public function __invoke()
    {
        return view('layouts.staff.system_admin.manage.teams.teams_index');
    }
}