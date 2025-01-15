<?php

namespace App\Http\Livewire\Requester;

use App\Http\Traits\Requester\Tickets;
use Livewire\Component;

class TicketTab extends Component
{
    use Tickets;

    protected $listeners = ['loadTicketTab' => '$refresh'];

    public function render()
    {
        $onProcessTickets = $this->getOnProcessTickets();
        $closedTickets = $this->getClosedTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $claimedTickets = $this->getClaimedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();
        $overdueTickets = $this->getOverdueTickets();

        $openTickets = $this->getOpenTickets();

        return view(
            'livewire.requester.ticket-tab',
            compact([
                'onProcessTickets',
                'closedTickets',
                'viewedTickets',
                'approvedTickets',
                'claimedTickets',
                'disapprovedTickets',
                'overdueTickets',
                'openTickets',
            ])
        );
    }
}