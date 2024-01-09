<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Livewire\Component;

class Open extends Component
{
    use Tickets;

    public function isTicketNeedLevelOfApproval(Ticket $ticket)
    {
        return TicketApproval::where('ticket_id', $ticket->id)->where('is_need_level_of_approval', true)->exists();
    }

    public function render()
    {
        $openTickets = $this->getOpenTickets();
        $forApprovalTickets = $this->getForApprovalTickets();

        return view('livewire.approver.ticket-status.open', [
            'openTickets' => $openTickets,
            'forApprovalTickets' => $forApprovalTickets,
        ]);
    }
}