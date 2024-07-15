<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\HelpTopicApprover;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use App\Notifications\AppNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ApproveTicket extends Component
{
    public Ticket $ticket;

    /**
     * Perform livewire events upon form submission.
     */
    private function actionOnSubmit()
    {
        $this->emit('loadDropdownApprovalButton');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadTicketStatusButtonHeader');
        $this->emit('loadSlaTimer');
        $this->emit('loadTicketTags');
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadTicketActions');
        $this->emit('loadLevelOfApproval');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadReplyButtonHeader');
        $this->emit('loadDisapprovalReason');
        $this->emit('loadClarificationButtonHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function isCurrentLevelApprover()
    {
        return $this->ticket->helpTopic->approvers()->where('user_id', auth()->user()->id)->exists();
    }

    public function approveTicket()
    {
        try {
            if (Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) && $this->isCurrentLevelApprover()) {
                DB::transaction(function () {
                    if ($this->ticket->status_id != Status::APPROVED && $this->ticket->approval_status != ApprovalStatusEnum::APPROVED) {
                        $this->ticket->update([
                            'status_id' => Status::APPROVED,
                            'approval_status' => ApprovalStatusEnum::APPROVED,
                            'svcdept_date_approved' => Carbon::now(),
                        ]);

                        // Retrieve the service department administrator responsible for approving the ticket. For notification use only
                        $serviceDepartmentAdmin = User::with('profile')
                            ->role(Role::SERVICE_DEPARTMENT_ADMIN)
                            ->where('id', auth()->user()->id)
                            ->first();

                        // Do this process if ticket's help topic has special project.
                        if ($this->ticket->isSpecialProject()) {
                            // Update ticket approval based on current approver (Level 1 - Service Dept. Admin)
                            TicketApproval::where('ticket_id', $this->ticket->id)
                                ->whereNotNull('approval_1->level_1_approver->approver_id')
                                ->whereJsonContains('approval_1->level_1_approver->approver_id', auth()->user()->id)
                                ->update([
                                    'approval_1->level_1_approver->approved_by' => auth()->user()->id,
                                    'approval_1->level_1_approver->is_approved' => true,
                                ]);

                            // Retrieve the newly updated record to filter the level 2 approver and send a notification.
                            $filteredLevel2Approvers = TicketApproval::where('ticket_id', $this->ticket->id)
                                ->whereJsonContains('approval_1->level_2_approver->is_approved', false)
                                ->whereNotNull('approval_1->level_2_approver->approver_id')
                                ->get();

                            if ($filteredLevel2Approvers->isNotEmpty()) {
                                $level2Approvers = User::with('profile')
                                    ->role(Role::APPROVER)
                                    ->whereIn('id', $filteredLevel2Approvers->pluck('approval_1.level_2_approver.approver_id')->flatten()->toArray())
                                    ->get();

                                if ($level2Approvers->isNotEmpty()) {
                                    $level2Approvers->each(function ($level2Approver) use ($serviceDepartmentAdmin) {
                                        Notification::send(
                                            $level2Approver,
                                            new AppNotification(
                                                ticket: $this->ticket,
                                                title: "Level 1 approved (Ticket #{$this->ticket->ticket_number})",
                                                message: "{$serviceDepartmentAdmin->profile->getFullName()} approved the level 1 approval. You can now approved the approval for level 2"
                                            )
                                        );
                                    });
                                } else {
                                    AppErrorLog::getError("No level 2 approvers have been found");
                                }

                                ActivityLog::make($this->ticket->id, 'approved the level 1 approval');
                                $this->actionOnSubmit();

                            } else {
                                AppErrorLog::getError("Empty filters for level 2 approvers in ticket approval");
                            }
                        }

                        // Delete the ticket notification of the currently logged in service department admin.
                        auth()->user()->notifications->each(
                            fn($notification) => $notification->data['ticket']['id'] === $this->ticket->id ? $notification->delete() : null
                        );

                        // Notify the ticket sender/requester.
                        Notification::send(
                            $this->ticket->user,
                            new AppNotification(
                                ticket: $this->ticket,
                                title: "Approved Ticket {$this->ticket->ticket_number}",
                                message: "{$serviceDepartmentAdmin->profile->getFullName()} approved the level 1 approval"
                            )
                        );
                        $this->actionOnSubmit();
                        ActivityLog::make($this->ticket->id, 'approved the ticket');

                    } else {
                        noty()->addInfo('Ticket has already been approved by other service dept. admin');
                    }
                    return redirect()->route('staff.tickets.open_tickets');
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
        return view('livewire.staff.ticket.approve-ticket');
    }
}
