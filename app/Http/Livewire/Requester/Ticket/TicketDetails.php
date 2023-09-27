<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketDetails extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketDetails' => 'render'];

    public function render()
    {
        return view('livewire.requester.ticket.ticket-details');
    }
}