<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreGroupController extends Controller
{
    public function __invoke()
    {
        return view('layouts.staff.system_admin.manage.store_groups.store_group_index');
    }
}
