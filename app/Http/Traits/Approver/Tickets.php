<?php

namespace App\Http\Traits\Approver;

use App\Http\Traits\BasicModelQueries;
use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

trait Tickets
{
    use BasicModelQueries;

    public function getForApprovalTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::FOR_APPROVAL);
        })->withWhereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', function ($approver) {
                    $approver->where('users.id', auth()->user()->id);
                });
        })->withWhereHas('user.department', function ($department) {
            $department->where('id', auth()->user()->buDepartments->pluck('id')->first());
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getDisapprovedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::DISAPPROVED);
        })->withWhereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', function ($approver) {
                    $approver->where('users.id', auth()->user()->id);
                });
        })->withWhereHas('user.department', function ($department) {
            $department->where('id', auth()->user()->buDepartments->pluck('id')->first());
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getOpenTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })->withWhereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', function ($approver) {
                    $approver->where('users.id', auth()->user()->id);
                });
        })->withWhereHas('user.department', function ($department) {
            $department->where('id', auth()->user()->buDepartments->pluck('id')->first());
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getViewedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })->withWhereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', function ($approver) {
                    $approver->where('users.id', auth()->user()->id);
                });
        })->withWhereHas('user.department', function ($department) {
            $department->where('id', auth()->user()->buDepartments->pluck('id')->first());
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getApprovedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->withWhereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', function ($approver) {
                    $approver->where('users.id', auth()->user()->id);
                });
        })->withWhereHas('user.department', function ($department) {
            $department->where('id', auth()->user()->buDepartments->pluck('id')->first());
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getOnProcessTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })->withWhereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', function ($approver) {
                    $approver->where('users.id', auth()->user()->id);
                });
        })->withWhereHas('user.department', function ($department) {
            $department->where('id', auth()->user()->buDepartments->pluck('id')->first());
        })
            ->orderByDesc('created_at')
            ->get();
    }
}
