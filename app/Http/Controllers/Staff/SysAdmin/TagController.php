<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function __invoke()
    {
        return view('layouts.staff.system_admin.manage.tags.tag_index');
    }
}