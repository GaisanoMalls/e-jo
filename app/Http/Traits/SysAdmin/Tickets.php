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

    public function sysAdminGetOpentTickets()
    {
        $openTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $openTickets;
    }

    public function sysAdminGetClaimedTickets()
    {
        $claimedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLAIMED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $claimedTickets;
    }

    public function sysAdminGetOnProcessTickets()
    {
        $onProcessTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }

    public function sysAdminGetViewedTickets()
    {
        $viewedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $viewedTickets;
    }

    public function sysAdminGetReopenedTickets()
    {
        $reopenedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::REOPENED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $reopenedTickets;
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
                ->where(function ($approvalStatusQuery) {
                    $approvalStatusQuery->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::DISAPPROVED]);
                });
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $closedTickets;
    }
}