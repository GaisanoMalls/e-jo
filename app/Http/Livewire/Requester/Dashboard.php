<?php

namespace App\Http\Livewire\Requester;

use Livewire\Component;
use App\Http\Traits\Requester\Tickets as RequestserTickets;

class Dashboard extends Component
{
    use RequestserTickets;

    protected $listeners = ['loadDashboard' => 'mount'];

    public int $openTickets;
    public int $onProcessTickets;
    public int $viewedTickets;
    public int $approvedTickets;
    public int $disapprovedTickets;
    public int $claimedTickets;
    public int $overdueTickets;
    public int $closedTickets;

    public function render()
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
                'routeName' => "user.tickets.open_tickets"
            ],
            [
                'name' => 'Viewed',
                'color' => '#7ba504',
                'count' => $this->viewedTickets,
                'icon' => 'fa-eye',
                'routeName' => "user.tickets.viewed_tickets"
            ],
            [
                'name' => 'Approved',
                'color' => '#14532d',
                'count' => $this->approvedTickets,
                'icon' => 'fa-thumbs-up',
                'routeName' => "user.tickets.approved_tickets"
            ],
            [
                'name' => 'Disapproved',
                'color' => '#be123c',
                'count' => $this->disapprovedTickets,
                'icon' => 'fa-thumbs-down',
                'routeName' => "user.tickets.disapproved_tickets"
            ],
            [
                'name' => 'Claimed',
                'color' => '#78716c',
                'count' => $this->claimedTickets,
                'icon' => 'fa-solid fa-list-check',
                'routeName' => "user.tickets.claimed_tickets"
            ],
            [
                'name' => 'On Process',
                'color' => '#1e5e59',
                'count' => $this->onProcessTickets,
                'icon' => 'fa-gears',
                'routeName' => "user.tickets.on_process_tickets"
            ],
            [
                'name' => 'Overdue',
                'color' => '#EA001C',
                'count' => $this->overdueTickets,
                'icon' => 'fa-triangle-exclamation',
                'routeName' => "user.tickets.overdue_tickets"
            ],
            [
                'name' => 'Closed',
                'color' => '#4E4392',
                'count' => $this->closedTickets,
                'icon' => 'fa-envelope-circle-check',
                'routeName' => "user.tickets.closed_tickets"
            ]
        ];

        return view('livewire.requester.dashboard', compact([
            'totalTickets',
            'ticketStatuses',
        ]));
    }
}