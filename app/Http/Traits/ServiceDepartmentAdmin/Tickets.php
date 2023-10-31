<?php

namespace App\Http\Traits\ServiceDepartmentAdmin;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

trait Tickets
{
    public function serviceDeptAdminGetTicketsToAssign(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->where('status_id', '!=', Status::CLAIMED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->whereHas('user', fn($query) => $query->where('users.department_id', auth()->user()->department_id));
        })->whereNull('team_id')->orWhereNull('team_id')->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetApprovedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->whereHas('user', fn($query) => $query->where('users.department_id', auth()->user()->department_id));
        })->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetDisapprovedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::DISAPPROVED)
                ->where('approval_status', ApprovalStatus::DISAPPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->whereHas('user', fn($query) => $query->where('users.department_id', auth()->user()->department_id));
        })->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetOpentTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::FOR_APPROVAL);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->whereHas('user', fn($query) => $query->where('users.department_id', auth()->user()->department_id));
        })->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetClaimedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLAIMED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->whereHas('user', fn($query) => $query->where('users.department_id', auth()->user()->department_id));
        })->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetOnProcessTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->whereHas('user', fn($query) => $query->where('users.department_id', auth()->user()->department_id));
        })->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetViewedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->whereHas('user', fn($query) => $query->where('users.department_id', auth()->user()->department_id));
        })->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetOverdueTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->whereHas('user', fn($query) => $query->where('users.department_id', auth()->user()->department_id));
        })->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetClosedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->whereHas('user', fn($query) => $query->where('users.department_id', auth()->user()->department_id));
        })->orderByDesc('created_at')->get();
    }
}
