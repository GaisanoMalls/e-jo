<?php

namespace App\Http\Traits\Approver;

use App\Http\Traits\BasicModelQueries;
use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    use BasicModelQueries;

    public function getForApprovalTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::FOR_APPROVAL);
        })->whereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'));
        })->whereHas('user.department', function ($department) {
            $department->whereIn('id', auth()->user()->buDepartments->pluck('id'));
        })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getDisapprovedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::DISAPPROVED);
        })->whereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'));
        })->whereHas('user.department', function ($department) {
            $department->whereIn('id', auth()->user()->buDepartments->pluck('id'));
        })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOpenTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })->whereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'));
        })->whereHas('user.department', function ($department) {
            $department->whereIn('id', auth()->user()->buDepartments->pluck('id'));
        })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getViewedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })->whereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'));
        })->whereHas('user.department', function ($department) {
            $department->whereIn('id', auth()->user()->buDepartments->pluck('id'));
        })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getApprovedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->whereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'));
        })->whereHas('user.department', function ($department) {
            $department->whereIn('id', auth()->user()->buDepartments->pluck('id'));
        })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOnProcessTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })->whereHas('helpTopic.levels', function ($query) {
            $query->whereIn('level_id', auth()->user()->levels->pluck('id'));
        })->whereHas('user.department', function ($department) {
            $department->whereIn('id', auth()->user()->buDepartments->pluck('id'));
        })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}