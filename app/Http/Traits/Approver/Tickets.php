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
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->withWhereHas(
                'ticketApprovals',
                fn($approval) =>
                $approval->whereJsonContains([
                    'level_1_approver->approver_id' => !null,
                    'level_1_approver->is_approved' => true,
                ])
            )
            ->orderByDesc('created_at')->get();
    }

    public function getDisapprovedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLOSED)->where('approval_status', ApprovalStatus::DISAPPROVED))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }

    public function getOpenTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::OPEN)->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }

    public function getViewedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::VIEWED)->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }

    public function getApprovedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::APPROVED)->where('approval_status', ApprovalStatus::APPROVED))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }

    public function getOnProcessTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::ON_PROCESS)->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->orderByDesc('created_at')->get();
    }
}
