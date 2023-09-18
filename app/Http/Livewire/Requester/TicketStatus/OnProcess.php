<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Livewire\Component;

class OnProcess extends Component
{
    use Tickets;

    public function render()
    {
        $onProcessTickets = $this->getOnProcessTickets();
        return view('livewire.requester.ticket-status.on-process', compact('onProcessTickets'));
    }
}