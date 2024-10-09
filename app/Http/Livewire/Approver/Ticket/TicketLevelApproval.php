<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
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
        return TicketApproval::where('ticket_id', $this->ticket->id)->first()?->approval_1['level_2_approver']['approved_by'];
    }

    public function approveTicketApproval1level2Approver()
    {
        try {
            if (auth()->user()->hasRole(Role::APPROVER)) {
                DB::transaction(function () {
                    $this->ticket->update(['status_id' => Status::APPROVED]);
                    TicketApproval::where('ticket_id', $this->ticket->id)
                        ->where(fn($level1Approver) => $level1Approver->whereNotNull([
                            'approval_1->level_1_approver->approver_id',
                            'approval_1->level_1_approver->approved_by',
                        ])->whereJsonContains('approval_1->level_1_approver->is_approved', true))
                        ->where(fn($level2Approver) => $level2Approver->whereNotNull('approval_1->level_2_approver->approver_id')
                            ->whereJsonContains('approval_1->level_2_approver->is_approved', false)
                            ->whereJsonContains('approval_1->level_2_approver->approver_id', auth()->user()->id))
                        ->update([
                            'approval_1->level_2_approver->approved_by' => auth()->user()->id,
                            'approval_1->level_2_approver->is_approved' => true,
                            'approval_1->is_all_approved' => true,
                        ]);

                    // Retrieve the approver responsible for approving the ticket. For notification use only
                    $level2Approver = User::with('profile')
                        ->role(Role::APPROVER)
                        ->where('id', auth()->user()->id)
                        ->first();

                    // Notify the requester
                    Notification::send(
                        $this->ticket->user,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Ticket #{$this->ticket->ticket_number} (Level of Approval)",
                            message: "{$level2Approver->profile->getFullName} approved the level 2 approval. Approvals for levels 1 and 2 have been completed."
                        )
                    );

                    // Get the agents.
                    $agents = User::role(Role::AGENT)
                        ->withWhereHas('branches', fn($query) => $query->whereIn('branches.id', [$this->ticket->branch_id]))
                        ->withWhereHas('serviceDepartments', fn($query) => $query->whereIn('service_departments.id', [$this->ticket->service_department_id]))
                        ->get();

                    // Notify agents through email and app based notification.
                    $agents->each(function ($agent) {
                        // Mail::to($agent)->send(new ApprovedTicketMail($this->ticket, $agent));
                        Notification::send(
                            $agent,
                            new AppNotification(
                                ticket: $this->ticket,
                                title: "Ticket #{$this->ticket->ticket_number} (New)",
                                message: "You have a new ticket",
                            )
                        );
                    });
                });

                ActivityLog::make(ticket_id: $this->ticket->id, description: 'approved the level 2 approval');
                $this->actionOnSubmit();
            } else {
                noty()->addError('You have no rights/permission to approve the ticket');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
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
                            'status_id' => Status::APPROVED,
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

                        $this->actionOnSubmit();
                    } else {
                        noty()->addWarning('Amount for special project has not yet approved.');
                    }
                });
            } else {
                noty()->addError('You have no rights/permission to approve the ticket');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.approver.ticket.ticket-level-approval');
    }
}
