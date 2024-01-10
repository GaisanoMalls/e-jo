<?php

namespace App\Http\Traits\Approver;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\BasicModelQueries;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    use BasicModelQueries;

    public function getForApprovalTickets()
    {
        return Ticket::has('helpTopic.specialProject')->where('approval_status', ApprovalStatusEnum::APPROVED)
            ->whereNotIn('status_id', [Status::VIEWED, Status::DISAPPROVED, Status::APPROVED, Status::ON_PROCESS])
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->withWhereHas(
                'ticketApprovals',
                fn($approval) => $approval->whereJsonContains('level_2_approver->approver_id', auth()->user()->id)
            )->orderByDesc('created_at')->get();
    }

    public function getOpenTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::OPEN)->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->withWhereHas(
                'ticketApprovals',
                fn($approval) => $approval->whereJsonContains('level_2_approver->approver_id', auth()->user()->id)
            )->orderByDesc('created_at')->get();
    }

    public function getDisapprovedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLOSED)->where('approval_status', ApprovalStatusEnum::DISAPPROVED))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->withWhereHas(
                'ticketApprovals',
                fn($approval) => $approval->whereJsonContains('level_2_approver->approver_id', auth()->user()->id)
            )->orderByDesc('created_at')->get();
    }

    public function getViewedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::VIEWED)->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->withWhereHas(
                'ticketApprovals',
                fn($approval) => $approval->whereJsonContains('level_2_approver->approver_id', auth()->user()->id)
            )->orderByDesc('created_at')->get();
    }

    public function getApprovedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::APPROVED)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->withWhereHas(
                'ticketApprovals',
                fn($approval) => $approval->whereJsonContains('level_2_approver->approver_id', auth()->user()->id)
            )->orderByDesc('created_at')->get();
    }

    public function getOnProcessTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::ON_PROCESS)->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
            ->withWhereHas('user.buDepartments', fn($department) => $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray()))
            ->withWhereHas(
                'ticketApprovals',
                fn($approval) => $approval->whereJsonContains('level_2_approver->approver_id', auth()->user()->id)
            )->orderByDesc('created_at')->get();
    }
}
