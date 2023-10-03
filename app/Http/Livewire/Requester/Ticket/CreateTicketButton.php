<?php

namespace App\Http\Livewire\Requester\Ticket;

use Livewire\Component;

class CreateTicketButton extends Component
{

    public function clearModalErrorMessages()
    {
        $this->emit('clearTicketErrorMessages');
    }
    public function render()
    {
        return view('livewire.requester.ticket.create-ticket-button');
    }
}