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

    public function approveTicket()
    {
        $this->ticket->update([
            'status_id' => Status::APPROVED,
            'approval_status' => ApprovalStatus::APPROVED
        ]);

        sleep(1);
        $this->emit('loadTicketLogs');
        $this->emit('loadApprovalButtonHeader');
        $this->emit('loadTicketStatusHeaderText');
        $this->dispatchBrowserEvent('close-modal');
        flash()->addSuccess('Ticket has been approved');
        ActivityLog::make($this->ticket->id, 'approved the ticket');
    }

    public function render()
    {
        return view('livewire.approver.ticket.approve-ticket');
    }
}