<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class AssignedAgent extends Component
{
    public Ticket $ticket;

    public function render()
    {
        return view('livewire.requester.ticket.assigned-agent');
    }
}