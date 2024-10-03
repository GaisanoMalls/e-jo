<?php

namespace App\Http\Traits\ServiceDepartmentAdmin;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\Utils;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

trait Tickets
{
    use Utils;

    public function serviceDeptAdminGetTicketsToAssign(): array|Collection
    {
        return Ticket::whereHas('teams')
            ->withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::APPROVED)
                    ->where('approval_status', ApprovalStatusEnum::APPROVED);
            })
            ->where(function ($userQuery) {
                $userQuery->withWhereHas('user.branches', function ($query) {
                    $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                })->withWhereHas('user.buDepartments', function ($query) {
                    $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first());
                });
            })
            ->withWhereHas('ticketCosting', function ($ticketCosting) {
                $ticketCosting->withWhereHas('prFileAttachments', function ($prFile) {
                    $prFile->where([
                        ['is_approved_level_1_approver', true],
                        ['is_approved_level_2_approver', true],
                    ]);
                });
            })
            ->withWhereHas('ticketApprovals', function ($ticketApproval) {
                $ticketApproval->where('is_approved', true);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Filter the newly created tickets.
     * Condition: Requester and Service Dept. Admin - Match the Branch and BU Department.
     * Tickets are exclusively visible within their respective Business Unit (BU). 
     * Special Project - Costing: Include filter for ticket that has amount for special project and is approved.
     */
    public function serviceDeptAdminGetOpentTickets(): array|Collection
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::OPEN)
                    ->where('approval_status', ApprovalStatusEnum::FOR_APPROVAL);
            })
            ->where(function ($userQuery) {
                $userQuery->withWhereHas('user', function ($user) {
                    $user->withWhereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    })->withWhereHas('buDepartments', function ($department) {
                        $department->where('departments.id', auth()->user()->buDepartments->pluck('id')->first());
                    });
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
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::VIEWED)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::FOR_APPROVAL
                    ]);
            })
            ->where(function ($userQuery) {
                $userQuery->withWhereHas('user', function ($user) {
                    $user->withWhereHas('branches', function ($branch) {
                        $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                    })->withWhereHas('buDepartments', function ($department) {
                        $department->where('departments.id', auth()->user()->buDepartments->pluck('id')->first());
                    });
                });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetApprovedTickets(): array|Collection
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where('approval_status', ApprovalStatusEnum::APPROVED)
            ->whereIn('status_id', [Status::APPROVED, Status::OPEN])
            ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetDisapprovedTickets(): array|Collection
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(column: function ($statusQuery) {
                $statusQuery->where('status_id', Status::DISAPPROVED)
                    ->where('approval_status', ApprovalStatusEnum::DISAPPROVED);
            })
            ->where(function ($userQuery) {
                $userQuery->withWhereHas('user', function ($user) {
                    $user->withWhereHas('branches', function ($branch) {
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
            ->where(function ($userQuery) {
                $userQuery->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray())
                    ->where(function ($statusQuery) {
                        $statusQuery->where('status_id', Status::CLAIMED)->where('approval_status', ApprovalStatusEnum::APPROVED);
                    });
            })
            ->where(function ($query) {
                $query->withWhereHas('ticketApprovals.helpTopicApprover.approver', function ($approver) {
                    $approver->where('user_id', auth()->user()->id)
                        ->where('is_approved', true);
                });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetOnProcessTickets(): array|Collection
    {
        $ticketIsNotYetApproved = Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->with(['clarifications', 'helpTopic.specialProject'])
            ->where('approval_status', ApprovalStatusEnum::FOR_APPROVAL)
            ->where('status_id', Status::ON_PROCESS)
            ->exists();

        if ($ticketIsNotYetApproved) {
            return Ticket::with(['clarifications', 'helpTopic.specialProject'])
                ->withWhereHas('user', fn($user) => $user->withTrashed())
                ->where(function ($query) {
                    $query->whereHas('clarifications')
                        ->orWhereHas('helpTopic.specialProject');
                })
                ->where(column: function ($query) {
                    $query->where('status_id', Status::ON_PROCESS)
                        ->where('approval_status', ApprovalStatusEnum::FOR_APPROVAL);
                })
                ->where(function ($userQuery) {
                    $userQuery->withWhereHas('user', function ($user) {
                        $user->withWhereHas('branches', function ($branch) {
                            $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                        })->withWhereHas('buDepartments', function ($department) {
                            $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                        });
                    });
                })
                ->orderByDesc('created_at')
                ->get();
        } else {
            // if ($this->isApproved2LevelsOfApproverAndHasSpecialProject()) {
            // return Ticket::with(['replies', 'clarifications', 'helpTopic.specialProject'])
            //     ->where(function ($query) {
            //         $query->whereHas('replies')
            //             ->orWhereHas('clarifications')
            //             ->orWhereHas('helpTopic.specialProject');
            //     })
            //     ->where(function ($query) {
            //         $query->where('status_id', Status::ON_PROCESS)
            //             ->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]);
            //     })
            //     ->where(function ($withSpecialProjQuery) {
            //         $withSpecialProjQuery->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            //             ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            //     })
            //     ->orderByDesc('created_at')
            //     ->get();
            // } else {
            return Ticket::with(['replies', 'clarifications', 'helpTopic.specialProject'])
                ->withWhereHas('user', fn($user) => $user->withTrashed())
                ->where(function ($query) {
                    $query->whereHas('replies')
                        ->orWhereHas('clarifications')
                        ->orWhereHas('helpTopic.specialProject');
                })
                ->orWhere(function ($withSpecialProjQuery) {
                    $withSpecialProjQuery->withWhereHas('user', function ($user) {
                        $user->withWhereHas('branches', function ($branch) {
                            $branch->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                        })->withWhereHas('buDepartments', function ($department) {
                            $department->where('departments.id', auth()->user()->buDepartments->pluck('id')->first());
                        });
                    });
                })
                ->where(function ($query) {
                    $query->where('status_id', Status::ON_PROCESS)
                        ->whereIn('approval_status', [
                            ApprovalStatusEnum::APPROVED,
                            ApprovalStatusEnum::FOR_APPROVAL
                        ]);
                })

                ->where(function ($nonSpecialProjQuery) {
                    $nonSpecialProjQuery->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                        ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
                })
                ->orderByDesc('created_at')
                ->get();
            // }
        }
    }

    public function serviceDeptAdminGetOverdueTickets(): array|Collection
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::OVERDUE)
                    ->where('approval_status', ApprovalStatusEnum::APPROVED);
            })
            ->where(function ($userQuery) {
                $userQuery->withWhereHas('user.branches', function ($query) {
                    $query->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetClosedTickets(): array|Collection
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(column: function ($statusQuery) {
                $statusQuery->where('status_id', Status::CLOSED)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::DISAPPROVED
                    ]);
            })
            ->where(function ($userQuery) {
                $userQuery->withWhereHas('user', function ($user) {
                    $user->withWhereHas('branches', function ($branch) {
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
