<?php

namespace App\Http\Traits\SysAdmin;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

trait Tickets
{
    public function sysAdminGetApprovedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetDisapprovedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::DISAPPROVED)
                ->where('approval_status', ApprovalStatus::DISAPPROVED);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOpenTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetClaimedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLAIMED)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->whereNotNull('agent_id');
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOnProcessTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetViewedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOverdueTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetClosedTickets(): Collection
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->orderByDesc('created_at')
            ->get();
    }
}
