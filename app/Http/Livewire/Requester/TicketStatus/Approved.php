<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Livewire\Component;

class Approved extends Component
{
    use Tickets;

    public function render()
    {
        $approvedTickets = $this->getApprovedTickets();
        return view('livewire.requester.ticket-status.approved', compact('approvedTickets'));
    }
}