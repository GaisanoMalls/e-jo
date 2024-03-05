<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Enums\SpecialProjectStatusEnum;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\TicketCostingPRFile;
use App\Models\TicketSpecialProjectStatus;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    use Utils;

    public Ticket $ticket;

    protected $listeners = ['loadLevelOfApproval' => '$refresh'];

    private function actionOnSubmit()
    {
        $this->emit('loadLevelOfApproval');
        $this->emit('loadTicketLogs');
        $this->redirectRoute('approver.tickets.approved');
    }

    public function getLevel1Approvers()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)->get();
        return User::with('profile')
            ->whereIn('id', $ticketApproval->pluck('approval_1.level_1_approver.approver_id')->flatten()->toArray())
            ->get();
    }

    public function getLevel2Approvers()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)->get();
        return User::with('profile')
            ->whereIn('id', $ticketApproval->pluck('approval_1.level_2_approver.approver_id')->flatten()->toArray())
            ->get();
    }

    // Get the service dept admin who's allowed to approve the last approval
    public function isOnlyApproverForLastApproval()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)->get();
        $approverIds = $ticketApproval->pluck('approval_2.level_2_approver.approver_id')->flatten()->toArray();

        return in_array(auth()->user()->id, $approverIds)
            && auth()->user()->hasRole(Role::APPROVER)
            && $this->isTicketApproval2Level1Approved()
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

    public function ticketLevel1ApprovalApprovedBy()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)
            ->first()?->approval_1['level_1_approver']['approved_by'];
    }

    public function ticketLevel2ApprovalApprovedBy()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)
            ->first()?->approval_1['level_2_approver']['approved_by'];
    }

    public function approveTicketApproval1level2Approver()
    {
        try {
            TicketApproval::where('ticket_id', $this->ticket->id)
                ->where(function ($level1Approver) {
                    $level1Approver->whereNotNull([
                        'approval_1->level_1_approver->approver_id',
                        'approval_1->level_1_approver->approved_by',
                    ])->whereJsonContains('approval_1->level_1_approver->is_approved', true);
                })->where(function ($level2Approver) {
                    $level2Approver->whereNotNull('approval_1->level_2_approver->approver_id')
                        ->whereJsonContains('approval_1->level_2_approver->is_approved', false)
                        ->whereJsonContains('approval_1->level_2_approver->approver_id', auth()->user()->id);
                })->update([
                        'approval_1->level_2_approver->approved_by' => auth()->user()->id,
                        'approval_1->level_2_approver->is_approved' => true,
                        'approval_1->is_all_approved' => true,
                    ]);

            ActivityLog::make($this->ticket->id, 'approved the level 2 approval');
            $this->actionOnSubmit();
        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong.');
        }
    }

    // Final approval for Level 2 approver after the approval from costing
    public function approveApproval2Level2Approver()
    {
        try {
            if (auth()->user()->hasRole(Role::APPROVER)) {
                DB::transaction(function () {
                    if ($this->isDoneSpecialProjectAmountApproval($this->ticket)) {
                        Ticket::where('id', $this->ticket->id)->update([
                            'approval_status' => ApprovalStatusEnum::APPROVED
                        ]);

                        TicketApproval::where('ticket_id', $this->ticket->id)
                            ->whereNotNull('approval_2->level_2_approver->approver_id')
                            ->whereJsonContains('approval_2->level_2_approver->approver_id', auth()->user()->id)
                            ->update([
                                'approval_2->level_2_approver->approved_by' => auth()->user()->id,
                                'approval_2->level_2_approver->is_approved' => true,
                                'is_all_approval_done' => true
                            ]);

                        TicketCostingPRFile::where([['ticket_costing_id', $this->ticket->ticketCosting->id]])
                            ->update(['is_approved_level_2_approver' => true]);

                        if (TicketApproval::where([['ticket_id', $this->ticket->id], ['is_all_approval_done', true]])->exists()) {
                            TicketSpecialProjectStatus::create([
                                'ticket_id' => $this->ticket->id,
                                'costing_and_planning_status' => SpecialProjectStatusEnum::DONE
                            ]);
                        }

                        $this->actionOnSubmit();
                    } else {
                        noty()->addWarning('Amount for special project has not yet approved.');
                    }
                });
            } else {
                noty()->addError('You have no rights/permission to approve the ticket');
            }
        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.approver.ticket.ticket-level-approval');
    }
}
