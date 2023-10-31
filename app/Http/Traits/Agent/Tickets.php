<?php

namespace App\Http\Traits\Agent;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;

trait Tickets
{
    public function agentGetOpenTickets(): Builder
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('branch_id', auth()->user()->branch_id)
                ->where('service_department_id', auth()->user()->service_department_id);
        })
            ->whereIn('team_id', auth()->user()->teams->pluck('id'))
            ->orderByDesc('created_at')
            ->get();
    }

    public function agentGetClaimedTickets(): Builder
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLAIMED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->whereNotNull('agent_id')
                ->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('service_department_id', auth()->user()->service_department_id);
        })->orderByDesc('created_at')
            ->get();
    }

    public function agentGetOnProcessTickets(): Builder
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [ApprovalStatus::FOR_APPROVAL, ApprovalStatus::APPROVED]);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('service_department_id', auth()->user()->service_department_id);
        })->orderByDesc('created_at')
            ->get();
    }

    public function agentGetOverdueTickets(): Builder
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OVERDUE)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('service_department_id', auth()->user()->service_department_id);
        })->orderByDesc('created_at')
            ->get();
    }

    public function agentGetClosedTickets(): Builder
    {
        return Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })->where(function ($byUserQuery) {
            $byUserQuery->where('agent_id', auth()->user()->id)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('service_department_id', auth()->user()->service_department_id);
        })->orderByDesc('created_at')
            ->get();
    }
}
