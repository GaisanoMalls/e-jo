<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadTicketStatusHeaderText extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketStatusHeaderText' => 'render'];

    public function render()
    {
        return view('livewire.requester.ticket.load-ticket-status-header-text');
    }
}