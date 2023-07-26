<?php

namespace App\Http\Traits\ServiceDepartmentAdmin;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function serviceDeptAdminGetApprovedTickets()
    {
        $approvedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->where('service_department_id', auth()->user()->service_department_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $approvedTickets;
    }

    public function serviceDeptAdminGetDisapprovedTickets()
    {
        $approvedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::DISAPPROVED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->where('service_department_id', auth()->user()->service_department_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $approvedTickets;
    }

    public function serviceDeptAdminGetOpentTickets()
    {
        $openTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->where('status_id', '!=', Status::CLAIMED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->where('service_department_id', auth()->user()->service_department_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $openTickets;
    }

    public function serviceDeptAdminGetClaimedTickets()
    {
        $claimedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLAIMED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->where('service_department_id', auth()->user()->service_department_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $claimedTickets;
    }

    public function serviceDeptAdminGetOnProcessTickets()
    {
        $onProcessTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->where('service_department_id', auth()->user()->service_department_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }

    public function serviceDeptAdminGetViewedTickets()
    {
        $viewedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->where('service_department_id', auth()->user()->service_department_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $viewedTickets;
    }

    public function serviceDeptAdminGetOverdueTickets()
    {
        $overdueTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->where(function ($byUserQuery) {
                $byUserQuery->where('branch_id', auth()->user()->branch_id)
                    ->where('service_department_id', auth()->user()->service_department_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $overdueTickets;
    }

    public function serviceDeptAdminGetClosedTickets()
    {
        $closedTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::CLOSED)
                ->where(function ($byUserQuery) {
                    $byUserQuery->where('branch_id', auth()->user()->branch_id)
                        ->where('service_department_id', auth()->user()->service_department_id);
                });
        })
            ->orWhere(function ($statusQuery) {
                $statusQuery->where('approval_status', ApprovalStatus::APPROVED)
                    ->where('approval_status', ApprovalStatus::DISAPPROVED);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $closedTickets;
    }
}