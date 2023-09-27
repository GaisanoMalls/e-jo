<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class ReasonOfDisapproval extends Component
{
    public Ticket $ticket;

    public function render()
    {
        $reason = $this->ticket->reasons()->where('ticket_id', $this->ticket->id)->first();
        return view('livewire.requester.ticket.reason-of-disapproval', [
            'reason' => $reason
        ]);
    }
}