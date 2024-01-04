<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadLevelOfApproval' => '$refresh'];

    public function getLevel1Approver()
    {
        $ticketApproval = TicketApproval::withWhereHas('ticket', fn($ticket) => $ticket->where('tickets.id', $this->ticket->id))->get();
        return User::with('profile')->whereIn('id', $ticketApproval->pluck('level_1_approver.approver_id')->flatten()->toArray())->get();
    }

    public function level1Approve()
    {
        TicketApproval::whereHas('ticket', fn($query) => $query->where('tickets.id', $this->ticket->id))
            ->whereJsonContains('level_1_approver->is_approved', false)->update([
                    'level_1_approver->is_approved' => true,
                    'approved_by' => auth()->user()->id,
                ]);
        $this->emit('loadLevelOfApproval');
    }

    public function undoLevel1Approve()
    {
        TicketApproval::whereHas('ticket', fn($query) => $query->where('tickets.id', $this->ticket->id))
            ->whereJsonContains('level_1_approver->is_approved', true)->update([
                    'level_1_approver->is_approved' => false,
                    'approved_by' => null,
                ]);
        $this->emit('loadLevelOfApproval');
    }

    public function isTicketApprovalApproved()
    {
        return $this->ticket->ticketApprovals->filter(fn($approval) => data_get($approval->level_1_approver, 'is_approved') === true)->isNotEmpty();
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-level-approval', [
            'level1Approvers' => $this->getLevel1Approver(),
            'isTicketApprovalApproved' => $this->isTicketApprovalApproved(),
        ]);
    }
}
