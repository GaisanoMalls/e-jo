<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Mail\Requester\TicketCreatedMail;
use App\Mail\Staff\ApprovedTicketMail;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\LevelApprover;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Requester\TicketCreatedNotification;
use App\Notifications\ServiceDepartmentAdmin\ApprovedTicketForAgentNotification;
use App\Notifications\ServiceDepartmentAdmin\ApprovedTicketForRequesterNotification;
use Exception;
use Illuminate\Support\Facades\DB;
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
        $this->emit('loadTicketTags');
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadTicketActions');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadReplyButtonHeader');
        $this->emit('loadDisapprovalReason');
        $this->emit('loadDropdownApprovalButton');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadTicketStatusButtonHeader');
        $this->emit('loadClarificationButtonHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function approveTicket()
    {
        try {
            DB::transaction(function () {
                // Update the ticket status if approved.
                $this->ticket->update([
                    'status_id' => Status::APPROVED,
                    'approval_status' => ApprovalStatus::APPROVED,
                ]);

                // Get the agents with specific conditions.
                $agents = User::withWhereHas('teams', fn($query) => $query->where('teams.id', $this->ticket->team_id))
                    ->whereHas('branches', fn($query) => $query->where('branches.id', $this->ticket->branch_id))
                    ->whereHas('serviceDepartments', fn($query) => $query->where('service_departments.id', $this->ticket->service_department_id))->get();

                // Notify agents through email and app based notification.
                foreach ($agents as $agent) {
                    Mail::to($agent)->send(new ApprovedTicketMail($this->ticket, $agent));
                    Notification::send($agent, new ApprovedTicketForAgentNotification($this->ticket));
                }

                // Notify approvers through email and app based notification.
                $levelApprovers = LevelApprover::where('help_topic_id', $this->ticket->helpTopic->id)->get();
                $approvers = User::approvers();

                if (!is_null($this->ticket->helpTopic)) {
                    foreach ($this->ticket->helpTopic->levels as $level) {
                        foreach ($levelApprovers as $levelApprover) {
                            foreach ($approvers as $approver) {
                                if ($approver->id == $levelApprover->user_id) {
                                    if ($levelApprover->level_id == $level->id) {
                                        if ($approver->buDepartments->pluck('id')->first() == $this->ticket->user->buDepartments->pluck('id')->first()) {
                                            Mail::to($approver)->send(new TicketCreatedMail($this->ticket, $approver));
                                            Notification::send($approver, new TicketCreatedNotification($this->ticket));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // Notify the ticket sender.
                Notification::send($this->ticket->user, new ApprovedTicketForRequesterNotification($this->ticket));
                ActivityLog::make($this->ticket->id, 'approved the ticket');
            });

            $this->actionOnSubmit();
            flash()->addSuccess('Ticket has been approved');

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.approve-ticket');
    }
}
