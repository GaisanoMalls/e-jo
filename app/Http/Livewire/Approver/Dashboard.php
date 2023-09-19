<?php

namespace App\Http\Livewire\Approver;

use Livewire\Component;
use App\Http\Traits\Approver\Tickets as ApproverTickets;

class Dashboard extends Component
{
    use ApproverTickets;

    public function render()
    {
        $openTickets = $this->getOpenTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();
        $onProcessTickets = $this->getOnProcessTickets();

        return view(
            'livewire.approver.dashboard',
            compact([
                'openTickets',
                'viewedTickets',
                'approvedTickets',
                'disapprovedTickets',
                'onProcessTickets'
            ])
        );
    }
}