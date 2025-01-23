<?php

namespace App\Http\Traits\ServiceDepartmentAdmin;

use App\Enums\ApprovalStatusEnum;
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
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->whereIn('approval_status', [ApprovalStatusEnum::FOR_APPROVAL, ApprovalStatusEnum::APPROVED]);
        })
            ->where(function ($ticket) {
                $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->orWhereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('buDepartments', function ($department) {
                        $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                    })
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    });
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Filter the newly created tickets. 
     * Condition: Requester and Service Dept. Admin - Match the Branch and BU Department.
     * Tickets are exclusively visible within their respective Business Unit (BU).
     */
    public function serviceDeptAdminGetViewedTickets(): array|Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [
                    ApprovalStatusEnum::APPROVED,
                    ApprovalStatusEnum::FOR_APPROVAL
                ]);
        })
            ->where(function ($ticket) {
                $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->orWhereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->withWhereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    })->whereHas('buDepartments', function ($department) {
                        $department->orWhereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                    });
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetApprovedTickets(): array|Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('approval_status', ApprovalStatusEnum::APPROVED)
                ->whereIn('status_id', [Status::APPROVED]);
        })
            ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray())
            ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            ->withWhereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('buDepartments', function ($department) {
                        $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                    })
                    ->whereHas('branches', function ($branch) {
                        $branch->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    });
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetDisapprovedTickets(): array|Collection
    {
        return Ticket::where(column: function ($statusQuery) {
            $statusQuery->where('status_id', Status::DISAPPROVED)
                ->where('approval_status', ApprovalStatusEnum::DISAPPROVED);
        })
            ->where(function ($ticket) {
                $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->orWhereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    })->whereHas('buDepartments', function ($department) {
                        $department->orWhereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
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
            ->whereHas('agent')
            ->whereNotNull('agent_id')
            ->where(function ($ticket) {
                $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->orWhereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals', function ($approver) {
                $approver->where('is_approved', true)
                    ->withWhereHas('helpTopicApprover', function ($approver) {
                        $approver->orWhere('user_id', auth()->user()->id);
                    });
            })
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    })->whereHas('buDepartments', function ($department) {
                        $department->orWhereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetOnProcessTickets(): array|Collection
    {
        return Ticket::with(['replies', 'clarifications', 'helpTopic.specialProject'])
            ->where(function ($status) {
                $status->where('status_id', Status::ON_PROCESS)
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
            ->where(function ($ticket) {
                $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->orWhereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->withWhereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    })->whereHas('buDepartments', function ($department) {
                        $department->orWhereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetOverdueTickets(): array|Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatusEnum::APPROVED);
        })
            ->where(function ($ticket) {
                $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->orWhereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->withWhereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    })->whereHas('buDepartments', function ($department) {
                        $department->orWhereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetClosedTickets(): array|Collection
    {
        return Ticket::where(column: function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->whereIn('approval_status', [
                    ApprovalStatusEnum::APPROVED,
                    ApprovalStatusEnum::DISAPPROVED
                ]);
        })
            ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray())
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->whereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('buDepartments', function ($department) {
                        $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                    })
                    ->whereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
