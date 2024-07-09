<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class Open extends Component
{
    use Tickets;

    public Collection $openTickets;

    public function mount()
    {
        $this->openTickets = $this->getOpenTickets();
    }

    public function render()
    {
        return view('livewire.requester.ticket-status.open');
    }
}