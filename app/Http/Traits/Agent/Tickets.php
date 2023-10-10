<?php

namespace App\Http\Traits\Agent;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function agentGetOpenTickets()
    {
        $openTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->where('service_department_id', auth()->user()->service_department_id);
        })
            ->whereIn('team_id', auth()->user()->teams->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return $openTickets;
    }

    public function agentGetClaimedTickets()
    {
        $claimedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLAIMED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->whereNotNull('agent_id')
                ->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('service_department_id', auth()->user()->service_department_id);
        })->orderBy('created_at', 'desc')
            ->get();

        return $claimedTickets;
    }

    public function agentGetOnProcessTickets()
    {
        $onProcessTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [ApprovalStatus::FOR_APPROVAL, ApprovalStatus::APPROVED]);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('service_department_id', auth()->user()->service_department_id);
        })->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }

    public function agentGetOverdueTickets()
    {
        $overdueTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('service_department_id', auth()->user()->service_department_id);
        })->orderBy('created_at', 'desc')
            ->get();

        return $overdueTickets;
    }

    public function agentGetClosedTickets()
    {
        $closedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::DISAPPROVED]);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('service_department_id', auth()->user()->service_department_id);
        })->orderBy('created_at', 'desc')
            ->get();

        return $closedTickets;
    }
}