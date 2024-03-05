<?php

namespace App\Http\Traits\SysAdmin;

use App\Enums\ApprovalStatusEnum;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;

trait Tickets
{
    public function sysAdminGetApprovedTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::APPROVED)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetDisapprovedTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::DISAPPROVED)->where('approval_status', ApprovalStatusEnum::DISAPPROVED))
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOpenTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::OPEN)->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetClaimedTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::CLAIMED)->where('approval_status', ApprovalStatusEnum::APPROVED)->whereNotNull('agent_id'))
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOnProcessTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::ON_PROCESS)->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetViewedTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::VIEWED)->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOverdueTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::OVERDUE)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetClosedTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::CLOSED)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->orderByDesc('created_at')
            ->get();
    }
}
