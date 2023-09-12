<?php

namespace App\Http\Livewire\Requester;

use App\Models\Ticket;
use Livewire\Component;

class TicketReplies extends Component
{
    public Ticket $ticket;
    public $replies = null;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function loadReplies()
    {
        $this->replies = $this->ticket->replies;
    }

    public function render()
    {
        return view('livewire.requester.ticket-replies', [
            'ticketReplies' => $this->ticket->replies
        ]);
    }
}