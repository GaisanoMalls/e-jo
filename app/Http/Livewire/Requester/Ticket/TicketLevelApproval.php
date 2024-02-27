<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    public Ticket $ticket;

    public function getLevel1Approvers()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)->get();
        return User::with('profile')->whereIn('id', $ticketApproval->pluck('approval_1.level_1_approver.approver_id')->flatten()->toArray())->get();
    }

    public function getLevel2Approvers()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)->get();
        return User::with('profile')->whereIn('id', $ticketApproval->pluck('approval_1.level_2_approver.approver_id')->flatten()->toArray())->get();
    }

    public function isTicketLevel1Approved()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->approval_1['level_1_approver'], 'approver_id') != null
            && data_get($approval->approval_1['level_1_approver'], 'approved_by') != null
            && data_get($approval->approval_1['level_1_approver'], 'is_approved') == true
        )->isNotEmpty();
    }

    public function isTicketLevel2Approved()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->approval_1['level_2_approver'], 'approver_id') != null
            && data_get($approval->approval_1['level_2_approver'], 'is_approved') == true
        )->isNotEmpty();
    }

    public function isApprovedByLevel2Approver()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->approval_1['level_2_approver'], 'is_approved') == true
            && data_get($approval->approval_1['level_2_approver'], 'is_approved') == true
        )->isNotEmpty();
    }

    public function ticketLevel1ApprovalApprovedBy()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)->first()?->approval_1['level_1_approver']['approved_by'];
    }

    public function ticketLevel2ApprovalApprovedBy()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)->first()?->approval_1['level_2_approver']['approved_by'];
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-level-approval', [
            'level1Approvers' => $this->getLevel1Approvers(),
            'level2Approvers' => $this->getLevel2Approvers(),
            'isTicketLevel1Approved' => $this->isTicketLevel1Approved(),
            'isTicketLevel2Approved' => $this->isTicketLevel2Approved(),
            'isApprovedByLevel2Approver' => $this->isApprovedByLevel2Approver(),
            'ticketLevel1ApprovalApprovedBy' => $this->ticketLevel1ApprovalApprovedBy(),
            'ticketLevel2ApprovalApprovedBy' => $this->ticketLevel2ApprovalApprovedBy(),
        ]);
    }
}
