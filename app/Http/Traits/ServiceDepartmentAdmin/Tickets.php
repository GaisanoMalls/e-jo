<?php

namespace App\Http\Traits\ServiceDepartmentAdmin;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function serviceDeptAdminGetTicketsToAssign()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::OPEN)->where('approval_status', ApprovalStatus::APPROVED)->where('status_id', '!=', Status::CLAIMED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->whereNull('team_id')->orWhereNull('team_id')->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetApprovedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::APPROVED)->where('approval_status', ApprovalStatus::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetDisapprovedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::DISAPPROVED)->where('approval_status', ApprovalStatus::DISAPPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetOpentTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::OPEN)->where('approval_status', ApprovalStatus::FOR_APPROVAL))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetClaimedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLAIMED)->where('approval_status', ApprovalStatus::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetOnProcessTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::ON_PROCESS)->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetViewedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::VIEWED)->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetOverdueTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::OVERDUE)->where('approval_status', ApprovalStatus::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetClosedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLOSED)->where('approval_status', ApprovalStatus::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }
}
