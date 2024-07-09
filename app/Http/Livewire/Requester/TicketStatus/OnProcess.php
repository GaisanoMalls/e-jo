<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class OnProcess extends Component
{
    use Tickets;

    public Collection $onProcessTickets;

    public function mount()
    {
        $this->onProcessTickets = $this->getOnProcessTickets();
    }

    public function render()
    {
        return view('livewire.requester.ticket-status.on-process');
    }
}