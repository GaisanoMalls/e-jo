<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Traits\Approver\Tickets as ApproverTickets;
use App\Http\Traits\Utils;

class ApproverDashboardController extends Controller
{
    public function index()
    {
        return view('layouts.staff.approver.includes.dashboard');
    }
}