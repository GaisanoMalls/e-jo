<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketLogs extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketLogs' => 'render'];

    public function render()
    {
        return view('livewire.requester.ticket.ticket-logs');
    }
}