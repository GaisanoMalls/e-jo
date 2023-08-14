<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('layouts.staff.system_admin.dashboard');
    }
}