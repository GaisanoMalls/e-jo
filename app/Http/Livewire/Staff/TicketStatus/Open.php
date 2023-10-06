<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use Livewire\Component;

class Open extends Component
{
    use TicketsByStaffWithSameTemplates;

    public function render()
    {
        $openTickets = $this->getOpenTickets();
        return view('livewire.staff.ticket-status.open', compact('openTickets'));
    }

    public function seenTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        if ($ticket->status_id != Status::VIEWED) {
            Ticket::findOrFail($id)->update(['status_id' => Status::VIEWED]);
            ActivityLog::make($id, 'seen the ticket');
        }
        return redirect()->route('staff.ticket.view_ticket', $id);
    }

}