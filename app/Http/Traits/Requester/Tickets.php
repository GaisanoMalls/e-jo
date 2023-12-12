<?php

namespace App\Http\Traits\Requester;

use App\Models\ApprovalStatus;
use App\Models\Clarification;
use App\Models\Reply;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function getOpenTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])->where('status_id', Status::OPEN)->where('user_id', auth()->user()->id)->orderByDesc('created_at')->get();
    }

    public function getOnProcessTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::ON_PROCESS)->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]))
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')->get();
    }

    public function getViewedTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::VIEWED)->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]))
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')->get();
    }

    public function getApprovedTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::APPROVED)->where('approval_status', ApprovalStatus::APPROVED))
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')->get();
    }

    public function getClaimedTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLAIMED)->where('approval_status', ApprovalStatus::APPROVED))
            ->whereNotNull('agent_id')->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')->get();
    }

    public function getDisapprovedTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::DISAPPROVED)->where('approval_status', ApprovalStatus::DISAPPROVED))
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')->get();
    }

    public function getClosedTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLOSED)->where('approval_status', ApprovalStatus::APPROVED))
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')->get();
    }

    public function getLatestReply(int $id)
    {
        return Reply::where('ticket_id', $id)->where('user_id', '!=', auth()->user()->id)->orderByDesc('created_at')->first();
    }

    public function getLatestClarification(int $id)
    {
        return Clarification::where('ticket_id', $id)->where('user_id', '!=', auth()->user()->id)->orderByDesc('created_at')->first();
    }
}
