<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Mail\Staff\ApprovedTicketMail;
use App\Models\ActivityLog;
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

    public function isCurrentLevelApprover()
    {
        return $this->ticket->helpTopic->withWhereHas('approvers', fn($approver) => $approver->where('user_id', auth()->user()->id))->get();
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

                        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)
                            ->withWhereHas('helpTopicApprover', function ($approver) {
                                $approver->where('help_topic_id', $this->ticket->help_topic_id)
                                ;
                            })->first();
                        $ticketApproval->update(['is_approved' => true]);

                        // Delete the ticket notification of the currently logged in service department admin.
                        auth()->user()->notifications->each(
                            fn($notification) => $notification->data['ticket']['id'] === $this->ticket->id ? $notification->delete() : null
                        );

                        // Get the service department administrator to which the ticket is intended.
                        $serviceDepartmentAdmins = User::with('profile')
                            ->withWhereHas('branches', function ($branch) {
                                $branch->where('branches.id', $this->ticket->branch_id);
                            })
                            ->withWhereHas('serviceDepartments', function ($serviceDepartment) {
                                $serviceDepartment->where('service_departments.id', $this->ticket->service_department_id);
                            })
                            ->role(Role::SERVICE_DEPARTMENT_ADMIN)
                            ->get();

                        $serviceDepartmentAdmins->each(function ($serviceDeptAdmin) {
                            Mail::to($serviceDeptAdmin)->send(new ApprovedTicketMail($this->ticket, $serviceDeptAdmin));
                            Notification::send(
                                $serviceDeptAdmin,
                                new AppNotification(
                                    ticket: $this->ticket,
                                    title: "Approved Ticket {$this->ticket->ticket_number}",
                                    message: "You have a new ticket. "
                                )
                            );
                        });

                        $agents = User::with('profile')
                            ->withWhereHas('teams', function ($team) {
                                $team->whereIn('teams.id', $this->ticket->teams->pluck('id')->toArray());
                            })
                            ->withWhereHas('serviceDepartments', function ($serviceDepartment) {
                                $serviceDepartment->where('service_departments.id', $this->ticket->service_department_id);
                            })
                            ->role(Role::AGENT)
                            ->get();

                        // Notify the agents through app and email.
                        $agents->each(function ($agent) {
                            Mail::to($agent)->send(new ApprovedTicketMail($this->ticket, $agent));
                            Notification::send(
                                $agent,
                                new AppNotification(
                                    ticket: $this->ticket,
                                    title: "Approved Ticket {$this->ticket->ticket_number}",
                                    message: "You have a new ticket. "
                                )
                            );
                        });

                        // Notify the ticket sender/requester.
                        Notification::send(
                            $this->ticket->user,
                            new AppNotification(
                                ticket: $this->ticket,
                                title: "Approved Ticket {$this->ticket->ticket_number}",
                                message: "{$serviceDepartmentAdmin->profile->getFullName} approved the level 1 approval"
                            )
                        );

                        $this->actionOnSubmit();
                        ActivityLog::make(ticket_id: $this->ticket->id, description: 'approved the ticket');

                    } else {
                        noty()->addInfo('Ticket has already been approved by other service dept. admin');
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
        return view('livewire.staff.ticket.approve-ticket');
    }
}
