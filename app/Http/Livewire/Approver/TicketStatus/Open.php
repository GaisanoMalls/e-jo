<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\Ticket;
use Livewire\Component;

class Open extends Component
{
    use Tickets;

    public function render()
    {
        $openTickets = $this->getOpenTickets();
        $forApprovalTickets = $this->getForApprovalTickets();

        return view('livewire.approver.ticket-status.open', [
            'openTickets' => $openTickets,
            'forApprovalTickets' => $forApprovalTickets,
        ]);
    }

    public function seenTicket($id)
    {
        dd(Ticket::findOrFail($id)->update(['status_id' => Status::VIEWED]));
        Ticket::findOrFail($id)->update(['status_id' => Status::VIEWED]);
        ActivityLog::make($id, 'seen the ticket');

        return redirect()->route('approver.ticket.view_ticket_details', $id);
    }
}