<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use Livewire\Component;

class Approved extends Component
{
    use Tickets;

    public function render()
    {
        $approvedTickets = $this->getApprovedTickets();
        return view('livewire.approver.ticket-status.approved', compact('approvedTickets'));
    }
}