<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Livewire\Component;

class Disapproved extends Component
{
    use Tickets;

    public function render()
    {
        $disapprovedTickets = $this->getDisapprovedTickets();
        return view('livewire.requester.ticket-status.disapproved', compact('disapprovedTickets'));
    }
}