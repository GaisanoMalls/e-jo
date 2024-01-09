<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use App\Notifications\ServiceDepartmentAdmin\ApprovedLevel1ApproverNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    public Ticket $ticket;
    public $isNeedLevelOfApproval = false;

    public function mount()
    {
        $this->isNeedLevelOfApproval = $this->isTicketNeedLevelOfApproval();
    }

    protected $listeners = ['loadLevelOfApproval' => 'render'];

    public function actionOnSubmit()
    {
        $this->emit('loadLevelOfApproval');
        $this->emit('loadTicketLogs');
    }

    public function showLevelApproval()
    {
        TicketApproval::where('ticket_id', $this->ticket->id)->update([
            'is_need_level_of_approval' => true,
        ]);
    }

    public function hideLevelApproval()
    {
        TicketApproval::where('ticket_id', $this->ticket->id)->update([
            'is_need_level_of_approval' => false,
        ]);
    }

    public function toggleAssignLevelOfApproval()
    {
        ($this->isNeedLevelOfApproval)
            ? $this->showLevelApproval()
            : $this->hideLevelApproval();
        $this->emit('loadLevelOfApproval');
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

    public function level1Approve()
    {
        TicketApproval::where('ticket_id', $this->ticket->id)->update([
            'level_1_approver->is_approved' => true,
            'level_1_approver->approved_by' => auth()->user()->id,
        ]);

        // Retrieve the updated record.
        $filteredLevel2Approvers = TicketApproval::where('ticket_id', $this->ticket->id)
            ->whereNotNull('level_1_approver->approver_id')
            ->whereJsonContains('level_1_approver->is_approved', true)->get();

        if ($filteredLevel2Approvers->isNotEmpty()) {
            $level2Approvers = User::with('profile')->whereIn('id', $filteredLevel2Approvers->pluck('level_2_approver.approver_id')->flatten()->toArray())->get();

            if ($level2Approvers->isNotEmpty()) {
                foreach ($level2Approvers as $level2Approver) {
                    Notification::send($level2Approver, new ApprovedLevel1ApproverNotification($this->ticket));
                }
            }

            ActivityLog::make($this->ticket->id, 'approved the level 1 approval');
            $this->actionOnSubmit();
        }
    }


    public function isTicketLevel1Approved()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->level_1_approver, 'approver_id') != null &&
            data_get($approval->level_1_approver, 'is_approved') == true
        )->isNotEmpty();
    }

    public function isTicketLevel2Approved()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->level_2_approver, 'approver_id') != null &&
            data_get($approval->level_2_approver, 'is_approved') == true
        )->isNotEmpty();
    }

    public function isApprovedByLevel2Approver()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->level_2_approver, 'is_approved') == true
            && $approval->is_all_approved == true
        )->isNotEmpty();
    }

    public function isTicketNeedLevelOfApproval()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval, 'is_need_level_of_approval') == true
        )->isNotEmpty();
    }

    public function ticketLevel1ApprovalApprovedBy()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)->first()->level_1_approver['approved_by'];
    }

    public function ticketLevel2ApprovalApprovedBy()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)->first()->level_2_approver['approved_by'];
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-level-approval', [
            'level1Approvers' => $this->getLevel1Approvers(),
            'level2Approvers' => $this->getLevel2Approvers(),
            'isTicketLevel1Approved' => $this->isTicketLevel1Approved(),
            'isTicketLevel2Approved' => $this->isTicketLevel2Approved(),
            'isApprovedByLevel2Approver' => $this->isApprovedByLevel2Approver(),
            'isTicketNeedLevelOfApproval' => $this->isTicketNeedLevelOfApproval(),
            'ticketLevel1ApprovalApprovedBy' => $this->ticketLevel1ApprovalApprovedBy(),
            'ticketLevel2ApprovalApprovedBy' => $this->ticketLevel2ApprovalApprovedBy(),
        ]);
    }
}
