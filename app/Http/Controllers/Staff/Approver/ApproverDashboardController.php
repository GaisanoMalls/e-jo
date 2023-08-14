<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Traits\Approver\Tickets as ApproverTickets;

class ApproverDashboardController extends Controller
{
    use ApproverTickets;

    public function index()
    {
        $openTickets = $this->getOpenTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();

        return view(
            'layouts.staff.approver.includes.dashboard',
            compact([
                'openTickets',
                'viewedTickets',
                'approvedTickets',
                'disapprovedTickets'
            ])
        );
    }
}