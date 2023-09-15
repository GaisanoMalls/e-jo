<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Livewire\Component;

class Claimed extends Component
{
    use Tickets;

    public function render()
    {
        $claimedTickets = $this->getClaimedTickets();
        return view('livewire.requester.ticket-status.claimed', compact('claimedTickets'));
    }
}