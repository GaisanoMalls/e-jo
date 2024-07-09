<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class Claimed extends Component
{
    use Tickets;

    public Collection $claimedTickets;

    public function mount()
    {
        $this->claimedTickets = $this->getClaimedTickets();
    }

    public function render()
    {
        return view('livewire.requester.ticket-status.claimed');
    }
}