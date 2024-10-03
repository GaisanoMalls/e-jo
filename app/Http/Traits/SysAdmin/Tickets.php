<?php

namespace App\Http\Traits\SysAdmin;

use App\Enums\ApprovalStatusEnum;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function sysAdminGetApprovedTickets()
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::APPROVED)
                    ->where('approval_status', ApprovalStatusEnum::APPROVED);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetDisapprovedTickets()
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(column: function ($statusQuery) {
                $statusQuery->where('status_id', Status::DISAPPROVED)
                    ->where('approval_status', ApprovalStatusEnum::DISAPPROVED);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOpenTickets()
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::OPEN)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::FOR_APPROVAL
                    ]);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetClaimedTickets()
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::CLAIMED)
                    ->where('approval_status', ApprovalStatusEnum::APPROVED)
                    ->whereNotNull('agent_id');
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOnProcessTickets()
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::ON_PROCESS)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::FOR_APPROVAL
                    ]);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetViewedTickets()
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(column: function ($statusQuery) {
                $statusQuery->where('status_id', Status::VIEWED)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::FOR_APPROVAL
                    ]);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetOverdueTickets()
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::OVERDUE)
                    ->where('approval_status', ApprovalStatusEnum::APPROVED);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function sysAdminGetClosedTickets()
    {
        return Ticket::withWhereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::CLOSED)
                    ->where('approval_status', ApprovalStatusEnum::APPROVED);
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
