<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class Closed extends Component
{
    use Tickets;

    public Collection $closedTickets;

    public function mount()
    {
        $this->closedTickets = $this->getClosedTickets();
    }

    public function render()
    {
        return view('livewire.requester.ticket-status.closed');
    }
}