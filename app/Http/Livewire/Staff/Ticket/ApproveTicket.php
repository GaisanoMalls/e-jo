<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\TicketApprovalLevel;
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
    use TicketApprovalLevel;

    public Ticket $ticket;

    private function triggerEvents()
    {
        $events = [
            'loadDropdownApprovalButton',
            'loadTicketStatusTextHeader',
            'loadTicketStatusButtonHeader',
            'loadSlaTimer',
            'loadTicketTags',
            'loadTicketLogs',
            'loadTicketDetails',
            'loadTicketActions',
            'loadLevelOfApproval',
            'loadBackButtonHeader',
            'loadReplyButtonHeader',
            'loadDisapprovalReason',
            'loadClarificationButtonHeader',
            'loadSidebarCollapseTicketStatus'
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    /**
     * Perform livewire events upon form submission.
     */
    private function actionOnSubmit()
    {
        $this->triggerEvents();
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
                        $this->updateTicketStatus();

                        // Retrieve the service department administrator responsible for approving the ticket. For notification use only
                        $serviceDepartmentAdmin = User::with('profile')
                            ->role(Role::SERVICE_DEPARTMENT_ADMIN)
                            ->where('id', auth()->user()->id)
                            ->first();

                        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)
                            ->withWhereHas('helpTopicApprover', function ($approver) {
                                $approver->where('help_topic_id', $this->ticket->help_topic_id);
                            })->first();

                        $ticketApproval->update(['is_approved' => true]);

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
                            // Mail::to($serviceDeptAdmin)->send(new ApprovedTicketMail($this->ticket, $serviceDeptAdmin));
                            Notification::send(
                                $serviceDeptAdmin,
                                new AppNotification(
                                    ticket: $this->ticket,
                                    title: "Ticket #{$this->ticket->ticket_number} (Approved)",
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
                            // Mail::to($agent)->send(new ApprovedTicketMail($this->ticket, $agent));
                            Notification::send(
                                $agent,
                                new AppNotification(
                                    ticket: $this->ticket,
                                    title: "Ticket #{$this->ticket->ticket_number} (Approved)",
                                    message: "You have a new ticket. "
                                )
                            );
                        });

                        // Notify the ticket sender/requester.
                        Notification::send(
                            $this->ticket->user,
                            new AppNotification(
                                ticket: $this->ticket,
                                title: "Ticket #{$this->ticket->ticket_number} (Approved)",
                                message: "{$serviceDepartmentAdmin->profile->getFullName} approved the level 1 approval"
                            )
                        );
                        // }

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

    // Function to check if all required approvals are granted
    private function updateTicketStatus()
    {
        $approvalLevels = [1, 2, 3, 4, 5];  // Define the levels of approval you're checking for

        // Count the number of approved levels
        $approvedCount = 0;
        $approvalCount = 0;

        foreach ($approvalLevels as $level) {
            // Count how many approvals are required at each level
            $approvalCount += TicketApproval::where('ticket_id', $this->ticket->id)
                ->withWhereHas('helpTopicApprover', function ($approver) use ($level) {
                    $approver->where([
                        ['help_topic_id', $this->ticket->helpTopic->id],
                        ['level', $level]
                    ]);
                })->count();

            // Count how many of those approvals are already approved
            $approvedCount += TicketApproval::where('ticket_id', $this->ticket->id)
                ->withWhereHas('helpTopicApprover', function ($approver) use ($level) {
                    $approver->where([
                        ['help_topic_id', $this->ticket->helpTopic->id],
                        ['level', $level]
                    ]);
                })
                ->where('is_approved', true)  // Assuming the status is 'approved'
                ->count();
        }

        // If all required approvals are approved, update ticket status to 'approved'
        if ($approvedCount === $approvalCount) {
            $this->ticket->update([
                'status_id' => Status::APPROVED,
                'approval_status' => ApprovalStatusEnum::APPROVED,
                'svcdept_date_approved' => Carbon::now(),
            ]);
        }
    }

    private function hasLevel1Approval()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where([
                    ['help_topic_id', $this->ticket->helpTopic->id],
                    ['level', 1]
                ]);
            })->exists();
    }

    private function hasLevel2Approval()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where([
                    ['help_topic_id', $this->ticket->helpTopic->id],
                    ['level', 2]
                ]);
            })->exists();
    }

    private function hasLevel3Approval()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where([
                    ['help_topic_id', $this->ticket->helpTopic->id],
                    ['level', 3]
                ]);
            })->exists();
    }

    private function hasLevel4Approval()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where([
                    ['help_topic_id', $this->ticket->helpTopic->id],
                    ['level', 4]
                ]);
            })->exists();
    }

    private function hasLevel5Approval()
    {
        return TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where([
                    ['help_topic_id', $this->ticket->helpTopic->id],
                    ['level', 5]
                ]);
            })->exists();
    }

    private function approveLevel1()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where([
                    ['help_topic_id', $this->ticket->helpTopic->id],
                    ['user_id', auth()->user()->id],
                ])->where('level', 1);
            })->first();

        if ($ticketApproval) {
            $ticketApproval->is_approved = true;  // Mark the approval as 'approved'
            $ticketApproval->save();

            // After approving, check if the ticket should be marked as approved
            $this->updateTicketStatus();
        }
    }

    private function approveLevel2()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where([
                    ['help_topic_id', $this->ticket->helpTopic->id],
                    ['user_id', auth()->user()->id],
                ])->where('level', 2);
            })->first();

        if ($ticketApproval) {
            $ticketApproval->is_approved = true;  // Mark the approval as 'approved'
            $ticketApproval->save();

            // After approving, check if the ticket should be marked as approved
            $this->updateTicketStatus();
        }
    }

    private function approveLevel3()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where([
                    ['help_topic_id', $this->ticket->helpTopic->id],
                    ['user_id', auth()->user()->id],
                ])->where('level', 3);
            })->first();

        if ($ticketApproval) {
            $ticketApproval->is_approved = true;  // Mark the approval as 'approved'
            $ticketApproval->save();

            // After approving, check if the ticket should be marked as approved
            $this->updateTicketStatus();
        }
    }

    private function approveLevel4()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where([
                    ['help_topic_id', $this->ticket->helpTopic->id],
                    ['user_id', auth()->user()->id],
                ])->where('level', 4);
            })->first();

        if ($ticketApproval) {
            $ticketApproval->is_approved = true;  // Mark the approval as 'approved'
            $ticketApproval->save();

            // After approving, check if the ticket should be marked as approved
            $this->updateTicketStatus();
        }
    }

    private function approvalLevel5()
    {
        $ticketApproval = TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where([
                    ['help_topic_id', $this->ticket->helpTopic->id],
                    ['user_id', auth()->user()->id],
                ])->where('level', 5);
            })->first();

        if ($ticketApproval) {
            $ticketApproval->is_approved = true;  // Mark the approval as 'approved'
            $ticketApproval->save();

            // After approving, check if the ticket should be marked as approved
            $this->updateTicketStatus();
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.approve-ticket');
    }
}
