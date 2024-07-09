<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
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
        return view('livewire.approver.ticket-status.disapproved');
    }
}