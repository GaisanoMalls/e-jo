<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\Ticket;
use Livewire\Component;

class CloseTicket extends Component
{
    public Ticket $ticket;

    private function actionOnSubmit()
    {
        sleep(1);
        $this->emit('loadTicketTags');
        $this->emit('loadTicketLogs');
        $this->emit('loadPriorityLevel');
        $this->emit('loadTicketActions');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadReplyButtonHeader');
        $this->emit('loadDropdownApprovalButton');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadTicketStatusButtonHeader');
        $this->emit('loadClarificationButtonHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function closeTicket()
    {
        $this->ticket->update(['status_id' => Status::CLOSED]);
        ActivityLog::make($this->ticket->id, 'closed the ticket');
        $this->actionOnSubmit();
        flash()->addSuccess('Ticket has been closed');
    }

    public function render()
    {
        return view('livewire.staff.ticket.close-ticket');
    }
}