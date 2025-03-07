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
            ->where('approval_status', ApprovalStatusEnum::APPROVED)
            ->whereIn('status_id', [Status::APPROVED, Status::OPEN])
            ->whereNull('agent_id')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                        ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                })
                    ->whereHas('teams', function ($team) {
                        $team->whereIn('teams.id', auth()->user()->teams->pluck('id'));
                    });
            })
            ->whereHas('ticketApprovals', function ($approval) {
                $approval->where('is_approved', true);
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
            ->whereNotNull('agent_id')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                        ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                })
                    ->whereHas('teams', function ($team) {
                        $team->whereIn('teams.id', auth()->user()->teams->pluck('id'));
                    });
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
            ->whereNotNull('agent_id')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                        ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                })
                    ->whereHas('teams', function ($team) {
                        $team->whereIn('teams.id', auth()->user()->teams->pluck('id'));
                    });
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
            ->whereNotNull('agent_id')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                        ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                })
                    ->whereHas('teams', function ($team) {
                        $team->whereIn('teams.id', auth()->user()->teams->pluck('id'));
                    });
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
            ->whereNotNull('agent_id')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('branch_id', auth()->user()->branches->pluck('id'))
                        ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id'));
                })
                    ->whereHas('teams', function ($team) {
                        $team->whereIn('teams.id', auth()->user()->teams->pluck('id'));
                    });
            })
            ->whereHas('ticketApprovals', function ($approval) {
                $approval->orWhere('is_approved', true);
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
