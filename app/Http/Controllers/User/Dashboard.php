<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\Requester\Tickets as RequestserTickets;

class Dashboard extends Controller
{
    use RequestserTickets;

    public function index()
    {
        $openTickets = $this->getOpenTickets();
        $onProcessTickets = $this->getOnProcessTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();
        $claimedTickets = $this->getClaimedTickets();
        $closedTickets = $this->getClosedTickets();

        return view(
            'layouts.user.includes.dashboard',
            compact([
                'openTickets',
                'onProcessTickets',
                'viewedTickets',
                'approvedTickets',
                'disapprovedTickets',
                'claimedTickets',
                'closedTickets'
            ])
        );
    }
}