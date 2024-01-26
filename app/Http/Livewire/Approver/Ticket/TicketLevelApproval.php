<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadLevelOfApproval' => '$refresh'];

    public function actionOnSubmit()
    {
        $this->emit('loadLevelOfApproval');
        $this->emit('loadTicketLogs');
    }

    public function getLevel1Approvers()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)->get();
        return User::with('profile')->whereIn('id', $ticketApproval->pluck('level_1_approver.approver_id')->flatten()->toArray())->get();
    }

    public function getLevel2Approvers()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)->get();
        return User::with('profile')->whereIn('id', $ticketApproval->pluck('level_2_approver.approver_id')->flatten()->toArray())->get();
    }

    public function level2Approve()
    {
        TicketApproval::where('ticket_id', $this->ticket->id)
            ->where(function ($level1Approver) {
                $level1Approver->whereNotNull([
                    'level_1_approver->approver_id',
                    'level_1_approver->approved_by',
                ])->whereJsonContains('level_1_approver->is_approved', true);
            })->where(function ($level2Approver) {
                $level2Approver->whereNotNull('level_2_approver->approver_id')
                    ->whereJsonContains('level_2_approver->is_approved', false)
                    ->whereJsonContains('level_2_approver->approver_id', auth()->user()->id);
            })->update([
                    'level_2_approver->approved_by' => auth()->user()->id,
                    'level_2_approver->is_approved' => true,
                    'is_all_approved' => true,
                ]);

        ActivityLog::make($this->ticket->id, 'approved the level 2 approval');
        $this->actionOnSubmit();
    }

    public function isTicketLevel1Approved()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->level_1_approver, 'approver_id') != null
            && data_get($approval->level_1_approver, 'approved_by') != null
            && data_get($approval->level_1_approver, 'is_approved') == true
        )->isNotEmpty();
    }

    public function isTicketLevel2Approved()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->level_2_approver, 'approver_id') != null
            && data_get($approval->level_2_approver, 'is_approved') == true
        )->isNotEmpty();
    }

    public function isApprovedByLevel2Approver()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->level_2_approver, 'is_approved') == true
            && data_get($approval->level_2_approver, 'is_approved') == true
        )->isNotEmpty();
    }

    public function ticketLevel1ApprovalApprovedBy()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)->first()?->level_1_approver['approved_by'];
    }

    public function ticketLevel2ApprovalApprovedBy()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)->first()?->level_2_approver['approved_by'];
    }

    public function render()
    {
        return view('livewire.approver.ticket.ticket-level-approval', [
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
