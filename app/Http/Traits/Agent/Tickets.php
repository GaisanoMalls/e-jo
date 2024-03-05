<?php

namespace App\Http\Traits\Agent;

use App\Enums\ApprovalStatusEnum;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;

trait Tickets
{
    public function agentGetOpenTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::APPROVED)
            ->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn(Builder $byUserQuery) => $byUserQuery->where('branch_id', auth()->user()->branches->pluck('id')->first())
                ->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first()))
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetClaimedTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::CLAIMED)
            ->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn(Builder $byUserQuery) => $byUserQuery->whereNotNull('agent_id')
                ->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branches->pluck('id')->first())
                ->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first()))
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetOnProcessTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::ON_PROCESS)
            ->whereIn('approval_status', [ApprovalStatusEnum::FOR_APPROVAL, ApprovalStatusEnum::APPROVED]))
            ->where(fn(Builder $byUserQuery) => $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branches->pluck('id')->first())
                ->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first()))
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetOverdueTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::OVERDUE)
            ->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn(Builder $byUserQuery) => $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branches->pluck('id')->first())
                ->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first()))
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetClosedTickets()
    {
        return Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::CLOSED)
            ->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn(Builder $byUserQuery) => $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branches->pluck('id')->first())
                ->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first()))
            ->orderByDesc('created_at')
            ->get();
    }
}
