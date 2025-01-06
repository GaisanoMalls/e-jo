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
        $reason = $this->ticket->reason;
        return view('livewire.staff.ticket.load-disapproval-reason', [
            'reason' => $reason
        ]);
    }
}