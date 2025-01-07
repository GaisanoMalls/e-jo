<?php

namespace App\Http\Traits;

use App\Enums\RecommendationApprovalStatusEnum;
use App\Models\RecommendationApprovalLevel;
use App\Models\RecommendationApprover;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
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

        foreach ($this->levelsOfApproval as $level) {
            $recommendationApprovals = RecommendationApprovalLevel::with('approvers')
                ->where('level', $level)
                ->withWhereHas('recommendation', function ($recommendation) use ($ticket) {
                    $recommendation->where('ticket_id', $ticket->id);
                })->get();

            foreach ($recommendationApprovals as $recommendationApproval) {
                foreach ($recommendationApproval->approvers as $approver) {
                    if ($approver->approver_id === auth()->user()->id) {
                        if ($recommendationApproval->recommendation->approval_status !== RecommendationApprovalStatusEnum::APPROVED) {
                            $recommendationApproval->recommendation->update(['approval_status' => RecommendationApprovalStatusEnum::APPROVED]);
                            foreach ($recommendationApprovals as $otherRecommendationApproval) {
                                if (!$otherRecommendationApproval->recommendation->approval_status !== RecommendationApprovalStatusEnum::APPROVED) {
                                    $otherRecommendationApproval->recommendation->update(['approval_status' => RecommendationApprovalStatusEnum::APPROVED]);
                                }
                            }
                        }
                    }
                }
            }

            // Check if all approvals for the current level are complete
            if (!$recommendationApprovals->every(fn($approval) => $approval->recommendation->approval_status === RecommendationApprovalStatusEnum::APPROVED)) {
                $isAllLevelApproved = false;
            } else {
                $nextLevelApprovals = RecommendationApprovalLevel::with('approvers')
                    ->where('level', $currentLevel + 1)
                    ->withWhereHas('recommendation', function ($recommendation) use ($ticket) {
                        $recommendation->where('ticket_id', $ticket->id);
                    })->get();

                if ($nextLevelApprovals->isNotEmpty()) {
                    foreach ($nextLevelApprovals as $nextLevelApproval) {
                        $approver = $nextLevelApproval->approvers->first();
                        if ($approver->approver_id !== auth()->user()->id) {
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
    }
}