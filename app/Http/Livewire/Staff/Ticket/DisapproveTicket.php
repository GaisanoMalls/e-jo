<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use Livewire\Component;

class DisapproveTicket extends Component
{
    public Ticket $ticket;

    public function actionOnSubmit()
    {
        sleep(1);
        $this->emit('loadTicketTags');
        $this->emit('loadTicketDetails');
        $this->emit('loadTicketActions');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadReplyButtonHeader');
        $this->emit('loadTicketActivityLogs');
        $this->emit('loadDropdownApprovalButton');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadTicketStatusButtonHeader');
        $this->emit('loadClarificationButtonHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function disapproveTicket()
    {
        $this->ticket->update([
            'status_id' => Status::DISAPPROVED,
            'approval_status' => ApprovalStatus::DISAPPROVED
        ]);

        ActivityLog::make($this->ticket->id, 'disapproved the ticket');

        $this->actionOnSubmit();
        flash()->addSuccess('Ticket has been approved');
    }

    public function render()
    {
        return view('livewire.staff.ticket.disapprove-ticket');
    }
}