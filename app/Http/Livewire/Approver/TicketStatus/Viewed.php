<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Illuminate\Support\Collection;
use Livewire\Component;

class Viewed extends Component
{
    use Tickets;

    public Collection $viewedTickets;

    public function mount()
    {
        $this->viewedTickets = $this->getViewedTickets();
    }

    public function isTicketNeedLevelOfApproval(Ticket $ticket)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', false],
        ])->exists();
    }

    public function isTicketIsAllApproved(Ticket $ticket)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', true],
        ])->exists();
    }

    public function render()
    {
        return view('livewire.approver.ticket-status.viewed');
    }
}