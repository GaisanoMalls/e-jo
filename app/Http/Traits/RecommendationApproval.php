<?php

namespace App\Http\Traits;

use App\Enums\RecommendationApprovalStatusEnum;
use App\Models\RecommendationApprovalStatus;
use App\Models\RecommendationApprover;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

trait RecommendationApproval
{
    private array $levelsOfApproval = [1, 2, 3, 4, 5];

    protected function isApproverIsInRecommendationApprovers(Ticket $ticket)
    {
        return RecommendationApprover::where('approver_id', auth()->user()->id)
            ->withWhereHas('approvalLevel.recommendation', function ($recommendation) use ($ticket) {
                $recommendation->where('ticket_id', $ticket->id);
            })->exists();
    }

    private static function sendNotificationToNextApprover(Ticket $ticket, User $approver)
    {
        Notification::send($approver, new AppNotification(
            ticket: $ticket,
            title: "Ticket #{$ticket->ticket_number} (For approval)",
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

    private function approveRecommendationApproval(Ticket $ticket)
    {
        $isAllLevelApproved = true;
        $currentLevel = 1; // keep track of the current level

        try {
            foreach ($this->levelsOfApproval as $level) {
                $recommendationApprovers = RecommendationApprover::withWhereHas('recommendation', function ($recommendation) use ($level, $ticket) {
                    $recommendation->where('ticket_id', $ticket->id);
                })
                    ->where('level', $level)
                    ->get();

                foreach ($recommendationApprovers as $ticketApprover) {
                    if ($ticketApprover->approver_id === auth()->user()->id) {
                        if (!$ticketApprover->is_approved) {
                            // Check if prior levels are approved
                            $priorLevelsApproved = true;
                            for ($i = 1; $i < $level; $i++) {
                                $priorLevelApprovers = RecommendationApprover::withWhereHas('recommendation', function ($recommendation) use ($i, $ticket) {
                                    $recommendation->where('ticket_id', $ticket->id);
                                })
                                    ->where('level', $i)
                                    ->get();

                                if (
                                    !$priorLevelApprovers->every(function ($approver) {
                                        return $approver->is_approved;
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

                            $ticketApprover->update(['is_approved' => true]);
                            foreach ($recommendationApprovers as $otherApprover) {
                                if (!$otherApprover->is_approved) {
                                    $otherApprover->update(['is_approved' => true]);
                                }
                            }
                        }
                    }
                }

                if (
                    !$recommendationApprovers->every(function ($recommendationApprover) use ($ticket) {
                        return $recommendationApprover->is_approved;
                    })
                ) {
                    $isAllLevelApproved = false;
                } else {
                    $nextApprovers = RecommendationApprover::withWhereHas('recommendation', function ($recommendation) use ($currentLevel, $ticket) {
                        $recommendation->where('ticket_id', $ticket->id);
                    })
                        ->where('level', $currentLevel + 1)
                        ->get();

                    if ($nextApprovers->isNotEmpty()) {
                        foreach ($nextApprovers as $nextApprover) {
                            $approver = $nextApprover->approver;
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

            if ($isAllLevelApproved) {
                RecommendationApprovalStatus::withWhereHas('recommendation', function ($recommendation) use ($ticket) {
                    $recommendation->where('ticket_id', $ticket->id);
                })
                    ->update([
                        'approval_status' => RecommendationApprovalStatusEnum::APPROVED,
                        'date' => Carbon::now()
                    ]);
            }

            return true;

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            return false;
        }
    }
}