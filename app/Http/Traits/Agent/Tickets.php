<?php

namespace App\Http\Traits\Agent;

use App\Enums\ApprovalStatusEnum;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function agentGetOpenTickets()
    {
        $openTicketsQuery = Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::APPROVED)
            ->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->where('branch_id', auth()->user()->branches->pluck('id')->first())
                ->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first()))
            ->withWhereHas('teams', fn($team) => $team->whereIn('teams.id', auth()->user()->teams->pluck('id')->toArray()));

        $openTicketsQuery->when($openTicketsQuery->has('ticketApprovals'), function ($query) {
            $query->withWhereHas('ticketApprovals', fn($approval) => $approval->orWhere('is_approved', true));
        });

        return $openTicketsQuery
            ->orderByDesc('created_at')
            ->get();

    }

    public function agentGetClaimedTickets()
    {
        $claimedTicketsQuery = Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLAIMED)
            ->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->whereNotNull('agent_id')
                ->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branches->pluck('id')->first())
                ->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first()))
            ->withWhereHas('teams', fn($team) => $team->whereIn('teams.id', auth()->user()->teams->pluck('id')->toArray()));

        $claimedTicketsQuery->when($claimedTicketsQuery->has('ticketApprovals'), function ($query) {
            $query->withWhereHas('ticketApprovals', fn($approval) => $approval->orWhere('is_approved', true));
        });

        return $claimedTicketsQuery
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetOnProcessTickets()
    {
        $onProcessTicketsQuery = Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::ON_PROCESS)
            ->whereIn('approval_status', [ApprovalStatusEnum::FOR_APPROVAL, ApprovalStatusEnum::APPROVED]))
            ->where(fn($byUserQuery) => $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branches->pluck('id')->first())
                ->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first()))
            ->withWhereHas('teams', fn($team) => $team->whereIn('teams.id', auth()->user()->teams->pluck('id')->toArray()));

        $onProcessTicketsQuery->when($onProcessTicketsQuery->has('ticketApprovals'), function ($query) {
            $query->withWhereHas('ticketApprovals', fn($approval) => $approval->orWhere('is_approved', true));
        });

        return $onProcessTicketsQuery
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetOverdueTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::OVERDUE)
            ->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branches->pluck('id')->first())
                ->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first()))
            ->withWhereHas('teams', fn($team) => $team->whereIn('teams.id', auth()->user()->teams->pluck('id')->toArray()))
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetClosedTickets()
    {
        $closedTicketsQuery = Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLOSED)
            ->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branches->pluck('id')->first())
                ->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first()))
            ->withWhereHas('teams', fn($team) => $team->whereIn('teams.id', auth()->user()->teams->pluck('id')->toArray()));

        $closedTicketsQuery->when($closedTicketsQuery->has('ticketApprovals'), function ($query) {
            $query->withWhereHas('ticketApprovals', fn($approval) => $approval->orWhere('is_approved', true));
        });

        return $closedTicketsQuery
            ->orderByDesc('created_at')
            ->get();
    }
}
