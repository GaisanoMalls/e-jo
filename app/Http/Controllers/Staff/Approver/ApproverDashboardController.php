<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApproverDashboardController extends Controller
{
    public function index()
    {
        return view('layouts.staff.approver.base');
    }
}
