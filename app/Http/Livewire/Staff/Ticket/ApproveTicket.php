<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Reason;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ApproveTicket extends Component
{
    public Ticket $ticket;

    public function actionOnSubmit()
    {
        sleep(1);
        $this->emit('loadTicketTags');
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadTicketActions');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadReplyButtonHeader');
        $this->emit('loadDisapprovalReason');
        $this->emit('loadDropdownApprovalButton');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadTicketStatusButtonHeader');
        $this->emit('loadClarificationButtonHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function approveTicket()
    {
        try {
            $this->ticket->update([
                'status_id' => Status::APPROVED,
                'approval_status' => ApprovalStatus::APPROVED
            ]);

            ActivityLog::make($this->ticket->id, 'approved the ticket');
            $this->actionOnSubmit();
            flash()->addSuccess('Ticket has been approved');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.approve-ticket');
    }
}