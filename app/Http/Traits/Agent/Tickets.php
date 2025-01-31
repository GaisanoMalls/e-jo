<?php

namespace App\Http\Traits\Agent;

use App\Enums\ApprovalStatusEnum;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function agentGetOpenTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where([
                ['status_id', Status::APPROVED],
                ['approval_status', ApprovalStatusEnum::APPROVED]
            ])
            ->where(function ($query) {
                $query->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->whereHas('teams', callback: function ($team) {
                $team->whereIn('teams.id', auth()->user()->teams->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals', function ($approval) {
                $approval->orWhere('is_approved', true);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetClaimedTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where([
                ['status_id', Status::CLAIMED],
                ['approval_status', ApprovalStatusEnum::APPROVED]
            ])
            ->where(function ($query) {
                $query->whereNotNull('agent_id')
                    ->where('agent_id', auth()->user()->id)
                    ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->whereHas('teams', function ($team) {
                $team->whereIn('teams.id', auth()->user()->teams->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals', function ($approval) {
                $approval->orWhere('is_approved', true);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetOnProcessTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($query) {
                $query->where('status_id', Status::ON_PROCESS)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::FOR_APPROVAL,
                        ApprovalStatusEnum::APPROVED
                    ]);
            })
            ->whereHas('teams', function ($team) {
                $team->whereIn('teams.id', auth()->user()->teams->pluck('id')->toArray());
            })
            ->where(function ($query) {
                $query->orWhere('agent_id', auth()->user()->id)
                    ->orWhereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->orWhereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals', function ($approval) {
                $approval->orWhere('is_approved', true);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetOverdueTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where([
                ['status_id', Status::OVERDUE],
                ['approval_status', ApprovalStatusEnum::APPROVED]
            ])
            ->where(column: function ($query) {
                $query->where('agent_id', auth()->user()->id)
                    ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->whereHas('teams', callback: function ($team) {
                $team->whereIn('teams.id', auth()->user()->teams->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals', function ($approval) {
                $approval->orWhere('is_approved', true);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetClosedTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where([
                ['status_id', Status::CLOSED],
                ['approval_status', ApprovalStatusEnum::APPROVED]
            ])
            ->where(function ($query) {
                $query->where('agent_id', auth()->user()->id)
                    ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
            })
            ->whereHas('teams', function ($team) {
                $team->whereIn('teams.id', auth()->user()->teams->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals', function ($approval) {
                $approval->orWhere('is_approved', true);
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
