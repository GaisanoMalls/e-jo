<?php

namespace App\Http\Traits;

use App\Enums\ApprovalStatusEnum;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

trait TicketApprovalLevel
{
    private array $levelsOfApproval = [1, 2, 3, 4, 5];

    protected function isApproverIsInConfiguration(Ticket $ticket)
    {
        return TicketApproval::where('ticket_id', $ticket->id)
            ->whereHas('helpTopicApprover', function ($approver) use ($ticket) {
                $approver->where('user_id', auth()->user()->id)
                    ->where('help_topic_id', $ticket->help_topic_id);
            })->exists();
    }

    private static function sendNotificationToNextApprover(Ticket $ticket, User $approver)
    {
        Notification::send($approver, new AppNotification(
            ticket: $ticket,
            title: "Ticket #{$ticket->ticket_number} (New)",
            message: "You have a new ticket for approval",
        ));
    }

    private static function notifyAndEmailTicketServiceDepartmentAdmins(Ticket $ticket)
    {
        // Get the service department administrator to which the ticket is intended.
        $ticketServiceDepartmentAdmins = User::with('profile')
            ->withWhereHas('branches', function ($branch) use ($ticket) {
                $branch->where('branches.id', $ticket->branch_id);
            })
            ->withWhereHas('serviceDepartments', function ($serviceDepartment) use ($ticket) {
                $serviceDepartment->where('service_departments.id', $ticket->service_department_id);
            })
            ->role(Role::SERVICE_DEPARTMENT_ADMIN)
            ->get();

        $ticketServiceDepartmentAdmins->each(function ($serviceDeptAdmin) use ($ticket) {
            Mail::to($serviceDeptAdmin)->send(new ApprovedTicketMail($this->ticket, $serviceDeptAdmin));
            Notification::send(
                $serviceDeptAdmin,
                new AppNotification(
                    ticket: $ticket,
                    title: "Ticket #{$ticket->ticket_number} (Approved)",
                    message: "You have a new ticket."
                )
            );
        });
    }

    private static function notifyAndEmailRequesterServiceDepartmentAdmins(Ticket $ticket)
    {
        $requesterServiceDepartmentAdmins = User::with('profile')
            ->whereHas('buDepartments', function ($serviceDepartment) use ($ticket) {
                $serviceDepartment->whereIn('departments.id', $ticket->user->buDepartments->pluck('id')->toArray());
            })
            ->role(Role::SERVICE_DEPARTMENT_ADMIN)
            ->get();

        $requesterServiceDepartmentAdmins->each(function ($serviceDeptAdmin) use ($ticket) {
            Mail::to($serviceDeptAdmin)->send(new ApprovedTicketMail($this->ticket, $serviceDeptAdmin));
            Notification::send(
                $serviceDeptAdmin,
                new AppNotification(
                    ticket: $ticket,
                    title: "Ticket #{$ticket->ticket_number} (Approved)",
                    message: "You BU ticket has been approved"
                )
            );
        });
    }

    private static function notifyAndEmailRequester(Ticket $ticket)
    {
        Mail::to($serviceDeptAdmin)->send(new ApprovedTicketMail($this->ticket, $serviceDeptAdmin));
        Notification::send(
            $ticket->user,
            new AppNotification(
                ticket: $ticket,
                title: "Ticket #{$ticket->ticket_number} (Approved)",
                message: "Your ticket has been approved."
            )
        );
    }

    private static function notifyAndEmailServiceDepartmentAdminAndRequester(Ticket $ticket)
    {
        static::notifyAndEmailTicketServiceDepartmentAdmins($ticket);
        static::notifyAndEmailRequesterServiceDepartmentAdmins($ticket);
        static::notifyAndEmailRequester($ticket);
    }

    private function approveLevelOfApproval(Ticket $ticket)
    {
        $isAllLevelApproved = true;
        $currentLevel = 1; // keep track of the current level

        try {
            foreach ($this->levelsOfApproval as $level) {
                $ticketApprovals = TicketApproval::where('ticket_id', $ticket->id)
                    ->withWhereHas('helpTopicApprover', function ($query) use ($level, $ticket) {
                        $query->where([
                            ['help_topic_id', $ticket->helpTopic->id],
                            ['level', $level]
                        ]);
                    })->get();

                foreach ($ticketApprovals as $ticketApproval) {
                    if ($ticketApproval->helpTopicApprover->user_id === auth()->user()->id) {
                        if (!$ticketApproval->is_approved) {
                            // Check if prior levels are approved
                            $priorLevelsApproved = true;
                            for ($i = 1; $i < $level; $i++) {
                                $priorLevelApprovals = TicketApproval::where('ticket_id', $ticket->id)
                                    ->withWhereHas('helpTopicApprover', function ($query) use ($i, $ticket) {
                                        $query->where([
                                            ['help_topic_id', $ticket->helpTopic->id],
                                            ['level', $i]
                                        ]);
                                    })->get();

                                if (
                                    !$priorLevelApprovals->every(function ($approval) {
                                        return $approval->is_approved;
                                    })
                                ) {
                                    $priorLevelsApproved = false;
                                    break;
                                }
                            }

                            if (!$priorLevelsApproved) {
                                noty()->addInfo("Prior levels must be approved before approving this level.");
                                break;
                            }

                            $ticketApproval->update(['is_approved' => true]);
                            foreach ($ticketApprovals as $otherTicketApproval) {
                                if (!$otherTicketApproval->is_approved) {
                                    $otherTicketApproval->update(['is_approved' => true]);
                                }
                            }
                        }
                    }
                }

                // Check if all approvals for the current level are complete
                if (!$ticketApprovals->every(fn($approval) => $approval->is_approved)) {
                    $isAllLevelApproved = false;
                } else {
                    $nextLevelApprovals = TicketApproval::where('ticket_id', $ticket->id)
                        ->withWhereHas('helpTopicApprover', function ($query) use ($currentLevel, $ticket) {
                            $query->where([
                                ['help_topic_id', $ticket->helpTopic->id],
                                ['level', $currentLevel + 1],
                            ]);
                        })->get();

                    if ($nextLevelApprovals->isNotEmpty()) {
                        foreach ($nextLevelApprovals as $nextLevelApproval) {
                            $approver = $nextLevelApproval->helpTopicApprover->approver;
                            if ($approver->id !== auth()->user()->id) {
                                static::sendNotificationToNextApprover($ticket, $approver);
                            } else {
                                static::notifyAndEmailServiceDepartmentAdminAndRequester($ticket);
                                break;
                            }
                        }
                    }
                }
                $currentLevel++;
            }

            // Update ticket status if all levels are approved
            if ($isAllLevelApproved) {
                $ticket->update([
                    'status_id' => Status::APPROVED,
                    'approval_status' => ApprovalStatusEnum::APPROVED,
                    'svcdept_date_approved' => Carbon::now(),
                ]);
            }

            return true;
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            return false;
        }
    }

    private function isPriorLevelApproved(Ticket $ticket)
    {
        try {
            foreach ($this->levelsOfApproval as $level) {
                $ticketApprovals = TicketApproval::where('ticket_id', $ticket->id)
                    ->withWhereHas('helpTopicApprover', function ($query) use ($level, $ticket) {
                        $query->where([
                            ['help_topic_id', $ticket->helpTopic->id],
                            ['level', $level]
                        ]);
                    })->get();

                foreach ($ticketApprovals as $ticketApproval) {
                    if ($ticketApproval->helpTopicApprover->user_id === auth()->user()->id) {
                        if (!$ticketApproval->is_approved) {
                            // Check if prior levels are approved
                            $priorLevelsApproved = true;
                            for ($i = 1; $i < $level; $i++) {
                                $priorLevelApprovals = TicketApproval::where('ticket_id', $ticket->id)
                                    ->withWhereHas('helpTopicApprover', function ($query) use ($i, $ticket) {
                                        $query->where([
                                            ['help_topic_id', $ticket->helpTopic->id],
                                            ['level', $i]
                                        ]);
                                    })->get();

                                if (
                                    !$priorLevelApprovals->every(function ($approval) {
                                        return $approval->is_approved;
                                    })
                                ) {
                                    $priorLevelsApproved = false;
                                    break;
                                }
                            }

                            if (!$priorLevelsApproved) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }
                }
            }

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            return false;
        }
    }

    private function isApprovedForLevel(Ticket $ticket, int $level)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', true]
        ])->withWhereHas('helpTopicApprover', function ($approver) use ($level) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where('level', $level);
        })->exists();
    }

    private function isLevel1Approved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 1);
    }

    public function syncTicketApprovals(Ticket $ticket)
    {
        // Delete existing TicketApproval records for the ticket
        TicketApproval::where('ticket_id', $ticket->id)->delete();

        // Get the current approvers
        $approvers = User::role([Role::SERVICE_DEPARTMENT_ADMIN, Role::APPROVER])
            ->withWhereHas('helpTopicApprovals', function ($query) use ($ticket) {
                $query->withWhereHas('configuration', function ($config) use ($ticket) {
                    $config->with('approvers')
                        ->where('bu_department_id', $ticket->user->buDepartments->pluck('id')->first());
                });
            })->get();

        // Create new TicketApproval records
        $approvers->each(function ($approver) use ($ticket) {
            $approver->helpTopicApprovals->each(function ($helpTopicApproval) use ($ticket) {
                TicketApproval::create([
                    'ticket_id' => $ticket->id,
                    'help_topic_approver_id' => $helpTopicApproval->id,
                ]);
            });
        });
    }
}