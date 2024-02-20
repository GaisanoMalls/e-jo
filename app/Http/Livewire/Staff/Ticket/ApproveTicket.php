<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Mail\Staff\ApprovedTicketMail;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use App\Notifications\ServiceDepartmentAdmin\ApprovedLevel1ApproverNotification;
use App\Notifications\ServiceDepartmentAdmin\ApprovedTicketForAgentNotification;
use App\Notifications\ServiceDepartmentAdmin\ApprovedTicketForRequesterNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

    public function approveTicket()
    {
        if (Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN)) {
            try {
                DB::transaction(function () {
                    // Update the ticket status if approved.
                    if ($this->ticket->status_id != Status::APPROVED && $this->ticket->approval_status != ApprovalStatusEnum::APPROVED) {
                        $this->ticket->update([
                            'status_id' => Status::APPROVED,
                            'approval_status' => ApprovalStatusEnum::APPROVED,
                            'svcdept_date_approved' => Carbon::now(),
                        ]);
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
                                ->whereJsonContains('approval_1->level_1_approver->is_approved', true)
                                ->whereNotNull('approval_1->level_1_approver->approver_id')
                                ->get();

                            if ($filteredLevel2Approvers->isNotEmpty()) {
                                $level2Approvers = User::with('profile')->whereIn('id', $filteredLevel2Approvers->pluck('approval_1.level_1_approver.approver_id')->flatten()->toArray())->get();

                                if ($level2Approvers->isNotEmpty()) {
                                    foreach ($level2Approvers as $level2Approver) {
                                        Notification::send($level2Approver, new ApprovedLevel1ApproverNotification($this->ticket));
                                    }
                                }

                                ActivityLog::make($this->ticket->id, 'approved the level 1 approval');
                                $this->actionOnSubmit();
                            }
                        }

                        // Get the agents with specific conditions.
                        $agents = User::withWhereHas('teams', fn($query) => $query->where('teams.id', $this->ticket->team_id))
                            ->whereHas('branches', fn($query) => $query->where('branches.id', $this->ticket->branch_id))
                            ->whereHas('serviceDepartments', fn($query) => $query->where('service_departments.id', $this->ticket->service_department_id))->get();

                        // Notify agents through email and app based notification.
                        foreach ($agents as $agent) {
                            Mail::to($agent)->send(new ApprovedTicketMail($this->ticket, $agent));
                            Notification::send($agent, new ApprovedTicketForAgentNotification($this->ticket));
                        }

                        // Notify the ticket sender/requester.
                        Notification::send($this->ticket->user, new ApprovedTicketForRequesterNotification($this->ticket));
                        ActivityLog::make($this->ticket->id, 'approved the ticket');

                        $this->actionOnSubmit();
                        noty()->addSuccess('Ticket has been approved');

                    } else {
                        noty()->addInfo('Ticket has already been approved by other service dept. admin');
                    }

                    return redirect()->route('staff.tickets.open_tickets');
                });
            } catch (Exception $e) {
                Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
                noty()->addError('Oops, something went wrong');
            }
        } else {
            noty()->addWarning('You have no rights/permissions to approve the ticket');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.approve-ticket');
    }
}
