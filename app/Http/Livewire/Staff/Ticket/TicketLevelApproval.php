<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\TicketCostingPRFile;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    use Utils;

    public Ticket $ticket;

    protected $listeners = ['loadLevelOfApproval' => 'render'];

    private function actionOnSubmit()
    {
        $this->emit('loadLevelOfApproval');
        $this->emit('loadTicketLogs');
    }

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

    // Get the service dept admin who's allowed to approve the last approval
    public function isOnlyServiceDeptAdminForLastApproval()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)->get();
        $approverIds = $ticketApproval->pluck('approval_2.level_1_approver.approver_id')->flatten()->toArray();

        return in_array(auth()->user()->id, $approverIds)
            && auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN)
            && $this->isDoneSpecialProjectAmountApproval($this->ticket);
    }

    public function isTicketApproval1Level1Approved()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->approval_1['level_1_approver'], 'approver_id') != null
            && data_get($approval->approval_1['level_1_approver'], 'approved_by') != null
            && data_get($approval->approval_1['level_1_approver'], 'is_approved') == true
        )->isNotEmpty();
    }

    public function isTicketApproval1Level2Approved()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->approval_1['level_2_approver'], 'approver_id') != null
            && data_get($approval->approval_1['level_2_approver'], 'approved_by') != null
            && data_get($approval->approval_1['level_2_approver'], 'is_approved') == true
        )->isNotEmpty();
    }

    public function isTicketApproval2Level1Approved()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->approval_2['level_1_approver'], 'approver_id') != null
            && data_get($approval->approval_2['level_1_approver'], 'approved_by') != null
            && data_get($approval->approval_2['level_1_approver'], 'is_approved') == true
        )->isNotEmpty();
    }

    public function isTicketApproval2Level2Approved()
    {
        return $this->ticket->ticketApprovals->filter(
            fn($approval) => data_get($approval->approval_2['level_2_approver'], 'approver_id') != null
            && data_get($approval->approval_2['level_2_approver'], 'approved_by') != null
            && data_get($approval->approval_2['level_2_approver'], 'is_approved') == true
        )->isNotEmpty();
    }

    public function isRequesterDonePR(Ticket $ticket)
    {
        return $ticket->ticketCosting->has('prFileAttachments')->exists();
    }

    public function ticketLevel1ApprovalApprovedBy()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)->first()?->approval_1['level_1_approver']['approved_by'];
    }

    public function ticketLevel2ApprovalApprovedBy()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)->first()?->approval_1['level_2_approver']['approved_by'];
    }

    // Final approval for Level 1 approver after the approval from costing
    public function approveApproval2Level1Approver()
    {
        try {
            if (auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN)) {
                if ($this->isDoneSpecialProjectAmountApproval($this->ticket)) {
                    DB::transaction(function () {
                        TicketApproval::where('ticket_id', $this->ticket->id)
                            ->whereNotNull('approval_2->level_1_approver->approver_id')
                            ->whereJsonContains('approval_2->level_1_approver->approver_id', auth()->user()->id)
                            ->update([
                                'approval_2->level_1_approver->approved_by' => auth()->user()->id,
                                'approval_2->level_1_approver->is_approved' => true,
                            ]);

                        TicketCostingPRFile::where([['ticket_costing_id', $this->ticket->ticketCosting->id]])
                            ->update(['is_approved_level_1_approver' => true]);
                    });
                    $this->actionOnSubmit();
                } else {
                    noty()->addWarning('Amount for special project has not yet approved.');
                }
            } else {
                noty()->addError('You have no rights/permission to approve the ticket');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-level-approval');
    }
}
