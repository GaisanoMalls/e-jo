<?php

namespace App\Http\Traits\SysAdmin;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function sysAdminGetApprovedTickets()
    {
        $approvedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $approvedTickets;
    }

    public function sysAdminGetDisapprovedTickets()
    {
        $approvedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::DISAPPROVED);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $approvedTickets;
    }

    public function sysAdminGetOpenTickets()
    {
        $openTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $openTickets;
    }

    public function sysAdminGetClaimedTickets()
    {
        $claimedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLAIMED)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->where('agent_id', '!=', null);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $claimedTickets;
    }

    public function sysAdminGetOnProcessTickets()
    {
        $onProcessTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }

    public function sysAdminGetViewedTickets()
    {
        $viewedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $viewedTickets;
    }

    public function sysAdminGetOverdueTickets()
    {
        $overdueTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $overdueTickets;
    }

    public function sysAdminGetClosedTickets()
    {
        $closedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::DISAPPROVED]);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $closedTickets;
    }
}