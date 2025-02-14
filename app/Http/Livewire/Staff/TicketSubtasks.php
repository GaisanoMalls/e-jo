<?php

namespace App\Http\Livewire\Staff;

use App\Models\Ticket;
use Livewire\Component;

class TicketSubtasks extends Component
{
    public Ticket $ticket;

    public function render()
    {
        return view('livewire.staff.ticket-subtasks');
    }
}
