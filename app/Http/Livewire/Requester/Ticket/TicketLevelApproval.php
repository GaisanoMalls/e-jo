<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    public Ticket $ticket;

    public function fetchedApprovers()
    {
        // $approvers = 
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-level-approval');
    }
}
