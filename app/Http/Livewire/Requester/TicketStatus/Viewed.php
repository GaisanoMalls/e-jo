<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Livewire\Component;

class Viewed extends Component
{
    use Tickets;

    public function render()
    {
        $viewedTickets = $this->getViewedTickets();
        return view('livewire.requester.ticket-status.viewed', compact('viewedTickets'));
    }
}