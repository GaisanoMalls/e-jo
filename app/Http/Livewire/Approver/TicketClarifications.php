<?php

namespace App\Http\Livewire\Approver;

use App\Models\Ticket;
use Livewire\Component;

class TicketClarifications extends Component
{
    public Ticket $ticket;
    public $clarifications = null;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function loadClarifications()
    {
        $this->clarifications = $this->ticket->clarifications;
    }

    public function render()
    {
        return view('livewire.approver.ticket-clarifications', [
            'ticketClarifications' => $this->ticket->clarifications
        ]);
    }
}