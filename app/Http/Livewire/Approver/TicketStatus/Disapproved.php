<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use Livewire\Component;

class Disapproved extends Component
{
    use Tickets;

    public function render()
    {
        $disapprovedTickets = $this->getDisapprovedTickets();
        return view('livewire.approver.ticket-status.disapproved', compact('disapprovedTickets'));
    }
}