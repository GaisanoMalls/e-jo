<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketSubtask extends Component
{
    public Ticket $ticket;

    public function render()
    {
        return view('livewire.staff.ticket.ticket-subtask');
    }
}
