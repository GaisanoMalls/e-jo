<?php

namespace App\Http\Traits\Agent;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function agentGetOpenTickets()
    {
        $openTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->orWhere(function ($byUserQuery) {
                    $byUserQuery->where('branch_id', auth()->user()->branch_id)
                        ->where('service_department_id', auth()->user()->service_department_id)
                        ->where('team_id', auth()->user()->team_id);
                });
        })
            ->where('status_id', '!=', Status::CLAIMED)
            ->orderBy('created_at', 'desc')
            ->get();

        return $openTickets;
    }

    public function agentGetClaimedTickets()
    {
        $claimedTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::CLAIMED)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->orWhere(function ($byUserQuery) {
                    $byUserQuery->where('agent_id', auth()->user()->id)
                        ->where('branch_id', auth()->user()->branch_id)
                        ->where('service_department_id', auth()->user()->service_department_id)
                        ->where('team_id', auth()->user()->team_id);
                });
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $claimedTickets;
    }

    public function agentGetOnProcessTickets()
    {
        $onProcessTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::ON_PROCESS)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->orWhere(function ($byUserQuery) {
                    $byUserQuery->where('agent_id', auth()->user()->id)
                        ->where('branch_id', auth()->user()->branch_id)
                        ->where('service_department_id', auth()->user()->service_department_id)
                        ->where('team_id', auth()->user()->team_id);
                });
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }

    public function agentGetOverdueTickets()
    {
        $overdueTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->orWhere(function ($byUserQuery) {
                    $byUserQuery->where('agent_id', auth()->user()->id)
                        ->where('branch_id', auth()->user()->branch_id)
                        ->where('service_department_id', auth()->user()->service_department_id)
                        ->where('team_id', auth()->user()->team_id);
                });
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $overdueTickets;
    }

    public function agentGetClosedTickets()
    {
        $closedTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->orWhere(function ($byUserQuery) {
                    $byUserQuery->where('agent_id', auth()->user()->id)
                        ->where('branch_id', auth()->user()->branch_id)
                        ->where('service_department_id', auth()->user()->service_department_id)
                        ->where('team_id', auth()->user()->team_id);
                });
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $closedTickets;
    }
}