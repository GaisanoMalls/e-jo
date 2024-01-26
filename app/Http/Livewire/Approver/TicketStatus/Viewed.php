<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Livewire\Component;

class Viewed extends Component
{
    use Tickets;

    public function isTicketNeedLevelOfApproval(Ticket $ticket)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_all_approved', false],
        ])->exists();
    }

    public function isTicketIsAllApproved(Ticket $ticket)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_all_approved', true],
        ])->exists();
    }

    public function render()
    {
        $viewedTickets = $this->getViewedTickets();
        return view('livewire.approver.ticket-status.viewed', compact('viewedTickets'));
    }
}