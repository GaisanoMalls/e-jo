<?php

namespace App\Http\Livewire\Requester;

use Livewire\Component;
use App\Http\Traits\Requester\Tickets as RequestserTickets;

class Dashboard extends Component
{
    use RequestserTickets;

    protected $listeners = ['loadDashboard' => 'render'];

    public function render()
    {
        $openTickets = $this->getOpenTickets();
        $onProcessTickets = $this->getOnProcessTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();
        $claimedTickets = $this->getClaimedTickets();
        $closedTickets = $this->getClosedTickets();

        return view(
            'livewire.requester.dashboard',
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