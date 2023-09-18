<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Livewire\Component;

class Closed extends Component
{
    use Tickets;

    public function render()
    {
        $closedTickets = $this->getClosedTickets();
        return view('livewire.requester.ticket-status.closed', compact('closedTickets'));
    }
}