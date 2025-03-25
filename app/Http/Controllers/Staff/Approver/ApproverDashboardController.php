<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;

class ApproverDashboardController extends Controller
{
    public function index()
    {
        return view('layouts.staff.approver.includes.dashboard');
    }
}