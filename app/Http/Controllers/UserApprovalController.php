<?php

namespace App\Http\Controllers;

use App\Models\UserApproval;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    private function renderAccountView($viewName)
    {
        return view("layouts.staff.approver.account.$viewName");
    }

    public function openAccounts()
    {
        return $this->renderAccountView('user-approval');
    }

}
