<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Traits\Utils;
use App\Models\Ticket;
use Livewire\Component;

class TicketDetails extends Component
{
    use Utils;

    public Ticket $ticket;

    protected $listeners = ['loadTicketDetails' => '$refresh'];

    public function render()
    {
        return view('livewire.approver.ticket.ticket-details', [
            'isSlaApproved' => $this->isSlaApproved($this->ticket),
        ]);
    }
}