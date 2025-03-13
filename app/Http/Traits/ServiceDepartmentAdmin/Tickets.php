<?php

namespace App\Http\Traits\ServiceDepartmentAdmin;

use App\Enums\ApprovalStatusEnum;
use App\Enums\RecommendationApprovalStatusEnum;
use App\Http\Traits\Utils;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Collection;

trait Tickets
{
    use Utils;

    /**
     * Filter the newly created tickets.
     * Condition: Requester and Service Dept. Admin - Match the Branch and BU Department.
     * Tickets are exclusively visible within their respective Business Unit (BU).
     * Special Project - Costing: Include filter for ticket that has amount for special project and is approved.
     */
    public function serviceDeptAdminGetOpentTickets(): array|Collection
    {
        return Ticket::where(function ($query) {
            $query->where('status_id', Status::OPEN)
                ->whereIn('approval_status', [ApprovalStatusEnum::FOR_APPROVAL, ApprovalStatusEnum::APPROVED]);
        })
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id'));
                    })
                    ->whereHas('buDepartments', function ($department) {
                        $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
                    })
                    ->orWhereHas('tickets', function ($ticket) {
                        $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                            ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                    });
            })
            ->where(function ($query) {
                $query->whereHas('recommendations.approvers', function ($approver) {
                    $approver->where('recommendation_approvers.approver_id', auth()->user()->id);
                })
                    ->orWhereHas('recommendations', function ($recommendation) {
                        $recommendation->whereHas('approvalStatus', function ($status) {
                            $status->where('approval_status', RecommendationApprovalStatusEnum::PENDING);
                        });
                    })
                    ->orWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                        $approver->where('user_id', auth()->user()->id);
                    })
                    ->orWhere('has_reached_due_date', true);
            })
            ->orderByDesc('created_at')
            ->get();
        ;
    }

    /**
     * Filter the newly created tickets.
     * Condition: Requester and Service Dept. Admin - Match the Branch and BU Department.
     * Tickets are exclusively visible within their respective Business Unit (BU).
     */
    public function serviceDeptAdminGetViewedTickets(): array|Collection
    {
        return Ticket::where(function ($query) {
            $query->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [
                    ApprovalStatusEnum::APPROVED,
                    ApprovalStatusEnum::FOR_APPROVAL
                ]);
        })
            ->where(function ($query) {
                $query->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                    ->orWhereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
            })
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id'));
                    })
                    ->whereHas('buDepartments', function ($department) {
                        $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
                    });
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
        ;
    }

    public function serviceDeptAdminGetApprovedTickets(): array|Collection
    {
        return Ticket::where(function ($query) {
            $query->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatusEnum::APPROVED);
        })
            ->whereHas('user', function ($user) {
                // Group conditions for clarity
                $user->withTrashed()
                    ->where(function ($query) {
                    // User must have BOTH branches AND departments
                    $query->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id'));
                    })
                        ->whereHas('buDepartments', function ($department) {
                        $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
                    });
                })
                    ->orWhereHas('tickets', function ($ticket) {
                    $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                        ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetDisapprovedTickets(): array|Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::DISAPPROVED)
                ->where('approval_status', ApprovalStatusEnum::DISAPPROVED);
        })
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    })
                    ->whereHas('buDepartments', function ($department) {
                        $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetClaimedTickets(): array|Collection
    {
        return Ticket::where([
            ['status_id', Status::CLAIMED],
            ['approval_status', ApprovalStatusEnum::APPROVED]
        ])
            ->whereNotNull('agent_id')
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    })
                    ->whereHas('buDepartments', function ($department) {
                        $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                    })
                    ->orWhereHas('tickets', function ($ticket) {
                        $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                            ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetOnProcessTickets(): array|Collection
    {
        return Ticket::with(['replies', 'clarifications', 'helpTopic.specialProject'])
            ->where(function ($query) {
                $query->where('status_id', Status::ON_PROCESS)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::FOR_APPROVAL
                    ]);
            })
            ->where(function ($query) {
                $query->whereHas('replies')
                    ->orWhereHas('clarifications')
                    ->orWhereHas('helpTopic.specialProject');
            })
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->where(function ($query) {
                        // User must have BOTH branches AND departments
                        $query->whereHas('branches', function ($branch) {
                            $branch->whereIn('branches.id', auth()->user()->branches->pluck('id'));
                        })
                            ->whereHas('buDepartments', function ($department) {
                            $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
                        });
                    })
                    // OR the user has tickets in specific branches/service departments
                    ->orWhereHas('tickets', function ($ticket) {
                        $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                            ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetOverdueTickets(): array|Collection
    {
        return Ticket::where(function ($query) {
            $query->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatusEnum::APPROVED);
        })
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->where(function ($query) {
                        // User must have BOTH branches AND departments
                        $query->whereHas('branches', function ($branch) {
                            $branch->whereIn('branches.id', auth()->user()->branches->pluck('id'));
                        })
                            ->whereHas('buDepartments', function ($department) {
                            $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
                        });
                    })
                    // OR the user has tickets in specific branches/service departments
                    ->orWhereHas('tickets', function ($ticket) {
                        $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                            ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetClosedTickets(): array|Collection
    {
        return Ticket::where(function ($query) {
            $query->where('status_id', Status::CLOSED)
                ->whereIn('approval_status', [
                    ApprovalStatusEnum::APPROVED,
                    ApprovalStatusEnum::DISAPPROVED
                ]);
        })
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->where(function ($query) {
                        // User must have BOTH branches AND departments
                        $query->whereHas('branches', function ($branch) {
                            $branch->whereIn('branches.id', auth()->user()->branches->pluck('id'));
                        })
                            ->whereHas('buDepartments', function ($department) {
                            $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
                        });
                    })
                    // OR the user has tickets in specific branches/service departments
                    ->orWhereHas('tickets', function ($ticket) {
                        $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                            ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
