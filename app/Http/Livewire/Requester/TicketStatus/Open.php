<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Livewire\Component;

class Open extends Component
{
    use Tickets;

    public function render()
    {
        $openTickets = $this->getOpenTickets();

        return view('livewire.requester.ticket-status.open', compact('openTickets'));
    }
}