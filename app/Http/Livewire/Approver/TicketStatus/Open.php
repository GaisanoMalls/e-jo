<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\Approver\Tickets;
use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Illuminate\Support\Collection;
use Livewire\Component;

class Open extends Component
{
    use Tickets;

    public Collection $openTickets;

    public function mount()
    {
        $this->openTickets = $this->getOpenTickets();
    }

    public function seenTicket(Ticket $ticket)
    {
        if (
            $ticket->status_id != Status::VIEWED
            && $ticket->approval_status != ApprovalStatusEnum::APPROVED
            // || !$ticket->whereDoesntHave('recommendations')
        ) {
            $ticket->update(['status_id' => Status::VIEWED]);
            ActivityLog::make(ticket_id: $ticket->id, description: 'seen the ticket');

            auth()->user()->notifications->each(function ($notification) use ($ticket) {
                if (isset($notification->data['ticket']['id']) && $notification->data['ticket']['id'] == $ticket->id) {
                    $notification->markAsRead();
                }
            });
        }

        return redirect()->route('approver.ticket.view_ticket_details', $ticket->id);
    }

    public function render()
    {
        return view('livewire.approver.ticket-status.open');
    }
}