<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use Livewire\Component;

class ApproveTicket extends Component
{
    public Ticket $ticket;

    /** Perform livewire events upon form submission. */
    private function actionOnSubmit()
    {
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadApprovalButtonHeader');
        $this->emit('loadTicketStatusHeaderText');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function approveTicket()
    {
        $this->ticket->update([
            'status_id' => Status::APPROVED,
            'approval_status' => ApprovalStatus::APPROVED,
        ]);

        ActivityLog::make($this->ticket->id, 'approved the ticket');

        $this->actionOnSubmit();
        flash()->addSuccess('Ticket has been approved');
    }

    public function render()
    {
        return view('livewire.approver.ticket.approve-ticket');
    }
}
