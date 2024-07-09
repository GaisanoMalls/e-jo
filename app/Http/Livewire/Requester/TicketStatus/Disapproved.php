<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class Disapproved extends Component
{
    use Tickets;

    public Collection $disapprovedTickets;

    public function mount()
    {
        $this->disapprovedTickets = $this->getDisapprovedTickets();
    }

    public function render()
    {
        return view('livewire.requester.ticket-status.disapproved');
    }
}