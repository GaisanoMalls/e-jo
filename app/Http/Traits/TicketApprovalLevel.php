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
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
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
            // Mail::to($serviceDeptAdmin)->send(new ApprovedTicketMail($this->ticket, $serviceDeptAdmin));
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
            // Mail::to($serviceDeptAdmin)->send(new ApprovedTicketMail($this->ticket, $serviceDeptAdmin));
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
        // Mail::to($serviceDeptAdmin)->send(new ApprovedTicketMail($this->ticket, $serviceDeptAdmin));
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
                                static::sendNotificationToNextApprover($this->ticket, $approver);
                            } else {
                                static::notifyAndEmailServiceDepartmentAdminAndRequester($this->ticket);
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

    private function level1IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 1);
    }

    private function level2IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 2);
    }

    private function level3IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 3);
    }

    private function level4IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 4);
    }

    private function level5IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 5);
    }
}