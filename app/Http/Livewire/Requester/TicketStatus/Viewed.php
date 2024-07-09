<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class Viewed extends Component
{
    use Tickets;

    public Collection $viewedTickets;

    public function mount()
    {
        $this->viewedTickets = $this->getViewedTickets();
    }

    public function render()
    {
        return view('livewire.requester.ticket-status.viewed');
    }
}