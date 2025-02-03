<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
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
        return view('livewire.approver.ticket-status.closed');
    }
}
