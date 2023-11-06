<?php

namespace App\Http\Traits\SysAdmin;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function sysAdminGetApprovedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetDisapprovedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::DISAPPROVED)
                ->where('approval_status', ApprovalStatus::DISAPPROVED);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOpenTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetClaimedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLAIMED)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->whereNotNull('agent_id');
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOnProcessTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetViewedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOverdueTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetClosedTickets()
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderByDesc('created_at')
            ->get();
    }
}
