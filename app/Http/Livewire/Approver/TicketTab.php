<?php

namespace App\Http\Livewire\Approver;

use App\Http\Traits\Approver\Tickets;
use Livewire\Component;

class TicketTab extends Component
{
    use Tickets;

    public function render()
    {
        $openTickets = $this->getOpenTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();
        $onProcessTickets = $this->getOnProcessTickets();
        $forApprovalTickets = $this->getForApprovalTickets();

        return view('livewire.approver.ticket-tab',
            compact(
                [
                    'openTickets',
                    'viewedTickets',
                    'approvedTickets',
                    'disapprovedTickets',
                    'onProcessTickets',
                    'forApprovalTickets',
                ]
            )
        );
    }
}