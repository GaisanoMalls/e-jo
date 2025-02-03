<?php

namespace App\Http\Livewire\Approver;

use Livewire\Component;
use App\Http\Traits\Approver\Tickets as ApproverTickets;

class Dashboard extends Component
{
    use ApproverTickets;

    public int $openTickets;
    public int $viewedTickets;
    public int $approvedTickets;
    public int $disapprovedTickets;
    public int $onProcessTickets;
    public int $closedTickets;


    public function render()
    {
        $this->openTickets = $this->getOpenTickets()->count();
        $this->viewedTickets = $this->getViewedTickets()->count();
        $this->approvedTickets = $this->getApprovedTickets()->count();
        $this->disapprovedTickets = $this->getDisapprovedTickets()->count();
        $this->onProcessTickets = $this->getOnProcessTickets()->count();
        $this->closedTickets = $this->getClosedTickets()->count();

        $totalTickets = $this->openTickets + $this->viewedTickets + $this->approvedTickets + $this->disapprovedTickets + $this->onProcessTickets + $this->closedTickets;

        $ticketStatuses = [
            [
                'name' => 'Open',
                'color' => '#BEB34E',
                'count' => $this->openTickets,
                'icon' => 'fa-envelope-open-text',
                'routeName' => "approver.tickets.open"
            ],
            [
                'name' => 'Viewed',
                'color' => '#7ba504',
                'count' => $this->viewedTickets,
                'icon' => 'fa-eye',
                'routeName' => "approver.tickets.viewed"
            ],
            [
                'name' => 'Approved',
                'color' => '#14532d',
                'count' => $this->approvedTickets,
                'icon' => 'fa-thumbs-up',
                'routeName' => "approver.tickets.approved"
            ],
            [
                'name' => 'Disapproved',
                'color' => '#be123c',
                'count' => $this->disapprovedTickets,
                'icon' => 'fa-thumbs-down',
                'routeName' => "approver.tickets.disapproved"
            ],
            [
                'name' => 'On Process',
                'color' => '#1e5e59',
                'count' => $this->onProcessTickets,
                'icon' => 'fa-gears',
                'routeName' => "approver.tickets.on_process"
            ],
            [
                'name' => 'Closed',
                'color' => '#7384e7',
                'count' => $this->closedTickets,
                'icon' => 'fa-envelope-circle-check',
                'routeName' => "approver.tickets.closed"
            ],
        ];

        return view('livewire.approver.dashboard', compact([
            'ticketStatuses',
            'totalTickets'
        ]));
    }
}