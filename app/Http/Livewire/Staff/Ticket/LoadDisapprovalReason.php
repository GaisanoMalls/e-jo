<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadDisapprovalReason extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadDisapprovalReason' => '$refresh'];

    public function render()
    {
        $reason = $this->ticket->reasons()->where('ticket_id', $this->ticket->id)->first();
        return view('livewire.staff.ticket.load-disapproval-reason', [
            'reason' => $reason
        ]);
    }
}