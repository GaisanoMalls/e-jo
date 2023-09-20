<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;

class SLAController extends Controller
{
    public function __invoke()
    {
        return view('layouts.staff.system_admin.manage.sla.sla_index');
    }
}