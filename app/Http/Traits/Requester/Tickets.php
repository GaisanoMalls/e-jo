<?php

namespace App\Http\Traits\Requester;

use App\Models\ApprovalStatus;
use App\Models\Clarification;
use App\Models\Reply;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

trait Tickets
{
    public function getOpenTickets(): Collection
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where('status_id', Status::OPEN)
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getOnProcessTickets(): Collection
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::ON_PROCESS)
                    ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
            })
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getViewedTickets(): Collection
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::VIEWED)
                    ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
            })
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getApprovedTickets(): Collection
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::APPROVED)
                    ->where('approval_status', ApprovalStatus::APPROVED);
            })
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getClaimedTickets(): Collection
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::CLAIMED)
                    ->where('approval_status', ApprovalStatus::APPROVED);
            })
            ->whereNotNull('agent_id')
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getDisapprovedTickets(): Collection
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::DISAPPROVED)
                    ->where('approval_status', ApprovalStatus::DISAPPROVED);
            })
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getClosedTickets(): Collection
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::CLOSED)
                    ->where('approval_status', ApprovalStatus::APPROVED);
            })
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getLatestReply(int $id): ?Reply
    {
        return Reply::where('ticket_id', $id)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderByDesc('created_at')
            ->first();
    }

    public function getLatestClarification(int $id): ?Clarification
    {
        return Clarification::where('ticket_id', $id)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderByDesc('created_at')
            ->first();
    }
}
