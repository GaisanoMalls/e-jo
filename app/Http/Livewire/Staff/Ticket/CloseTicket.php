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

    private function triggerEvents()
    {
        $events = [
            'loadTicketTags',
            'loadTicketLogs',
            'loadPriorityLevel',
            'loadTicketActions',
            'loadBackButtonHeader',
            'loadReplyButtonHeader',
            'loadDropdownApprovalButton',
            'loadTicketStatusTextHeader',
            'loadTicketStatusButtonHeader',
            'loadClarificationButtonHeader',
            'loadSidebarCollapseTicketStatus'
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    private function actionOnSubmit()
    {
        $this->triggerEvents();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function closeTicket()
    {
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
        $this->actionOnSubmit();
        noty()->addSuccess('Ticket has been closed');
    }

    public function render()
    {
        return view('livewire.staff.ticket.close-ticket');
    }
}