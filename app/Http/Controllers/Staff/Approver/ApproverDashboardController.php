<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Traits\Approver\Tickets as ApproverTickets;
use App\Http\Traits\Utils;

class ApproverDashboardController extends Controller
{
    use ApproverTickets, Utils;

    public function index()
    {
        $openTickets = $this->getOpenTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();
        $onProcessTickets = $this->getOnProcessTickets();

        return (!$this->costingApprover2Only())
            ? view('layouts.staff.approver.includes.dashboard', compact([
                'openTickets',
                'viewedTickets',
                'approvedTickets',
                'disapprovedTickets',
                'onProcessTickets'
            ]))
            : abort(403, 'Unauthorized access');
    }
}