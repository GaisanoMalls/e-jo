<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    use TicketsByStaffWithSameTemplates;
    public int $openTickets;
    public int $viewedTickets;
    public int $approvedTickets;
    public int $disapprovedTickets;
    public int $claimedTickets;
    public int $onProcessTickets;
    public int $overdueTickets;
    public int $closedTickets;

    public function dashboard()
    {
        $this->openTickets = $this->getOpenTickets()->count();
        $this->viewedTickets = $this->getViewedTickets()->count();
        $this->approvedTickets = $this->getApprovedTickets()->count();
        $this->disapprovedTickets = $this->getDisapprovedTickets()->count();
        $this->claimedTickets = $this->getClaimedTickets()->count();
        $this->onProcessTickets = $this->getOnProcessTickets()->count();
        $this->overdueTickets = $this->getOverdueTickets()->count();
        $this->closedTickets = $this->getClosedTickets()->count();

        $totalTickets = $this->openTickets + $this->viewedTickets + $this->approvedTickets + $this->disapprovedTickets + $this->onProcessTickets + $this->overdueTickets + $this->closedTickets;

        $ticketStatuses = [
            [
                'name' => 'Open',
                'color' => '#BEB34E',
                'count' => $this->openTickets,
                'icon' => 'fa-envelope-open-text',
                'routeName' => "staff.tickets.open_tickets"
            ],
            [
                'name' => 'Viewed',
                'color' => '#7ba504',
                'count' => $this->viewedTickets,
                'icon' => 'fa-eye',
                'routeName' => "staff.tickets.viewed_tickets"
            ],
            [
                'name' => 'Approved',
                'color' => '#14532d',
                'count' => $this->approvedTickets,
                'icon' => 'fa-thumbs-up',
                'routeName' => "staff.tickets.approved_tickets"
            ],
            [
                'name' => 'Disapproved',
                'color' => '#be123c',
                'count' => $this->disapprovedTickets,
                'icon' => 'fa-thumbs-down',
                'routeName' => "staff.tickets.disapproved_tickets"
            ],
            [
                'name' => 'Claimed',
                'color' => '#78716c',
                'count' => $this->claimedTickets,
                'icon' => 'fa-solid fa-list-check',
                'routeName' => "staff.tickets.claimed_tickets"
            ],
            [
                'name' => 'On Process',
                'color' => '#1e5e59',
                'count' => $this->onProcessTickets,
                'icon' => 'fa-gears',
                'routeName' => "staff.tickets.on_process_tickets"
            ],
            [
                'name' => 'Overdue',
                'color' => '#EA001C',
                'count' => $this->overdueTickets,
                'icon' => 'fa-triangle-exclamation',
                'routeName' => "staff.tickets.overdue_tickets"
            ],
            [
                'name' => 'Closed',
                'color' => '#4E4392',
                'count' => $this->closedTickets,
                'icon' => 'fa-envelope-circle-check',
                'routeName' => "staff.tickets.closed_tickets"
            ]
        ];

        return view('layouts.staff.system_admin.dashboard', compact([
            'ticketStatuses',
            'totalTickets'
        ]));
    }
}