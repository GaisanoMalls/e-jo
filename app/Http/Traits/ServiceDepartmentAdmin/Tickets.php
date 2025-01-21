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
            ->withWhereHas('user', function ($user) {
                $user->withTrashed()
                    ->whereHas('buDepartments', function ($department) {
                        $department->orWhereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                    });
            })
            ->where(function ($userQuery) {
                $userQuery->withWhereHas('user.buDepartments', function ($department) {
                    $department->orWhereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetApprovedTickets(): array|Collection
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('approval_status', ApprovalStatusEnum::APPROVED)
                    ->whereIn('status_id', [Status::APPROVED]);
            })
            ->where(function ($ticket) {
                $ticket->withWhereHas('branch', function ($branch) {
                    $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                });
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
            ->where(function ($userQuery) {
                $userQuery->withWhereHas('user', function ($user) {
                    $user->withTrashed()
                        ->withWhereHas('branches', function ($branch) {
                            $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                        })->withWhereHas('buDepartments', function ($department) {
                            $department->where('departments.id', auth()->user()->buDepartments->pluck('id')->first());
                        });
                });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetClaimedTickets(): array|Collection
    {
        return Ticket::whereHas('agent')
            ->whereNotNull('agent_id')
            ->withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($query) {
                $query->withWhereHas('ticketApprovals', function ($approver) {
                    $approver->where('is_approved', true)
                        ->withWhereHas('helpTopicApprover', function ($approver) {
                            $approver->orWhere('user_id', auth()->user()->id);
                        });
                });
            })
            ->where(function ($userQuery) {
                $userQuery->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    // ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray())
    
                    ->where(function ($statusQuery) {
                        $statusQuery->where('status_id', Status::CLAIMED)
                            ->where('approval_status', ApprovalStatusEnum::APPROVED);
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetOnProcessTickets(): array|Collection
    {
        return Ticket::where(function ($query) {
            $query->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [
                    ApprovalStatusEnum::APPROVED,
                    ApprovalStatusEnum::FOR_APPROVAL
                ]);
        })
            ->with(['replies', 'clarifications', 'helpTopic.specialProject'])
            ->where(function ($query) {
                $query->whereHas('replies')
                    ->orWhereHas('clarifications')
                    ->orWhereHas('helpTopic.specialProject');
            })
            ->orWhere(function ($ticket) {
                $ticket->withWhereHas('user', function ($user) {
                    $user->withTrashed()
                        ->withWhereHas('branches', function ($branch) {
                            $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                        })->withWhereHas('buDepartments', function ($department) {
                            $department->where('departments.id', auth()->user()->buDepartments->pluck('id')->first());
                        });
                });
            })
            ->where(function ($ticket) {
                $ticket->withWhereHas('branch', function ($branch) {
                    $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                })->withWhereHas('serviceDepartment', function ($serviceDepartment) {
                    $serviceDepartment->whereIn('service_departments.id', auth()->user()->serviceDepartments->pluck('id')->toArray());
                });
            })
            ->withWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
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
            ->withWhereHas('user', function ($user) {
                $user->withTrashed()
                    ->withWhereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
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
            ->where(function ($ticket) {
                $ticket->withWhereHas('user', function ($user) {
                    $user->withTrashed()
                        ->withWhereHas('branches', function ($branch) {
                            $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                        })->withWhereHas('buDepartments', function ($department) {
                            $department->orWhere('departments.id', auth()->user()->buDepartments->pluck('id')->first());
                        });
                });
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
