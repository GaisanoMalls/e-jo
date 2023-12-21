<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Livewire\Component;

class Approvers extends Component
{
    public Ticket $ticket;

    public function render()
    {
        $approvers = User::approvers();

        return view('livewire.requester.ticket.approvers', compact('approvers'));
    }
}