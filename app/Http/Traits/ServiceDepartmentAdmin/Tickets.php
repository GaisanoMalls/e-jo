<?php

namespace App\Http\Traits\ServiceDepartmentAdmin;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function serviceDeptAdminGetTicketsToAssign()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->where('status_id', '!=', Status::CLAIMED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
            })
            ->whereNull('team_id')
            ->orWhereNull('team_id')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function serviceDeptAdminGetApprovedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function serviceDeptAdminGetDisapprovedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::DISAPPROVED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function serviceDeptAdminGetOpentTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->where('status_id', '!=', Status::CLAIMED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function serviceDeptAdminGetClaimedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLAIMED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function serviceDeptAdminGetOnProcessTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function serviceDeptAdminGetViewedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function serviceDeptAdminGetOverdueTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function serviceDeptAdminGetClosedTickets()
    {
        return Ticket::where(function ($query) {
            $query->where('status_id', Status::CLOSED)
                ->where(function ($byUserQuery) {
                    $byUserQuery->where('branch_id', auth()->user()->branch_id)
                        ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                });
        })
            ->orWhere(function ($statusQuery) {
                $statusQuery->where('approval_status', ApprovalStatus::APPROVED)
                    ->where('approval_status', ApprovalStatus::DISAPPROVED);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}