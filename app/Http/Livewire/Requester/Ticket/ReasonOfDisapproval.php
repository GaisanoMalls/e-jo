<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Reason;
use App\Models\Ticket;
use Livewire\Component;

class ReasonOfDisapproval extends Component
{
    public Ticket $ticket;
    public ?Reason $reason = null;

    public function mount()
    {
        $this->reason = $this->ticket->reason;
    }

    public function render()
    {
        return view('livewire.requester.ticket.reason-of-disapproval');
    }
}