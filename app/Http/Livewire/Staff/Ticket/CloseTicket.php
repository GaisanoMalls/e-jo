<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\Ticket;
use App\Notifications\AppNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class CloseTicket extends Component
{
    public Ticket $ticket;

    public function closeTicket()
    {
        if ($this->ticket->status_id !== Status::CLOSED) {
            $this->ticket->update(['status_id' => Status::CLOSED]);
            ActivityLog::make(ticket_id: $this->ticket->id, description: 'closed the ticket');
            Notification::send(
                $this->ticket->user,
                new AppNotification(
                    ticket: $this->ticket,
                    title: "Ticket #{$this->ticket->ticket_number} (Closed)",
                    message: "{$this->ticket->agent?->profile->getFullName} closed the ticket."
                )
            );
            noty()->addSuccess('Ticket has been closed.');
        } else {
            noty()->addWarning('Ticket has already been closed.');
        }
        return redirect()->route('staff.ticket.view_ticket', $this->ticket->id);
    }

    public function render()
    {
        return view('livewire.staff.ticket.close-ticket');
    }
}