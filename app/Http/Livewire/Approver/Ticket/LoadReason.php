<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadReason extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadReason' => '$refresh'];

    public function render()
    {
        $reason = $this->ticket->reasons()->where('ticket_id', $this->ticket->id)->first();
        return view('livewire.approver.ticket.load-reason', [
            'reason' => $reason
        ]);
    }
}