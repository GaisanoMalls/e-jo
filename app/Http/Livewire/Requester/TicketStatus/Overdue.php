<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Http\Traits\Requester\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class Overdue extends Component
{
    use Tickets;

    public Collection $overdueTickets;

    public function mount()
    {
        $this->overdueTickets = $this->getOverdueTickets();
    }

    public function render()
    {
        return view('livewire.requester.ticket-status.overdue');
    }
}
