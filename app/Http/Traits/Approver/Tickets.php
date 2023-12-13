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
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::OPEN)->where('approval_status', ApprovalStatus::FOR_APPROVAL))
            ->withWhereHas('helpTopic.levels', fn($query) => $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', fn($approver) => $approver->where('users.id', auth()->user()->id)))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }

    public function getDisapprovedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLOSED)->where('approval_status', ApprovalStatus::DISAPPROVED))
            ->withWhereHas('helpTopic.levels', fn($query) => $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', fn($approver) => $approver->where('users.id', auth()->user()->id)))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }

    public function getOpenTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::OPEN)->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]))
            ->withWhereHas('helpTopic.levels', fn($query) => $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', fn($approver) => $approver->where('users.id', auth()->user()->id)))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }

    public function getViewedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::VIEWED)->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]))
            ->withWhereHas('helpTopic.levels', fn($query) => $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', fn($approver) => $approver->where('users.id', auth()->user()->id)))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }

    public function getApprovedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::APPROVED)->where('approval_status', ApprovalStatus::APPROVED))
            ->withWhereHas('helpTopic.levels', fn($query) => $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', fn($approver) => $approver->where('users.id', auth()->user()->id)))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }

    public function getOnProcessTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::ON_PROCESS)->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]))
            ->withWhereHas('helpTopic.levels', fn($query) => $query->whereIn('level_id', auth()->user()->levels->pluck('id'))
                ->withWhereHas('approvers', fn($approver) => $approver->where('users.id', auth()->user()->id)))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }
}
