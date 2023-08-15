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
        $openTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where('status_id', Status::OPEN)
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $openTickets;
    }

    public function getOnProcessTickets()
    {
        $onProcessTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::ON_PROCESS)
                    ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
            })
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }

    public function getViewedTickets()
    {
        $viewedTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::VIEWED)
                    ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
            })
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $viewedTickets;
    }

    public function getApprovedTickets()
    {
        $approvedTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::APPROVED)
                    ->where('approval_status', ApprovalStatus::APPROVED);
            })
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $approvedTickets;
    }

    public function getDisapprovedTickets()
    {
        $disapprovedTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::CLOSED)
                    ->where('approval_status', ApprovalStatus::DISAPPROVED);
            })
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $disapprovedTickets;
    }

    public function getClosedTickets()
    {
        $onProcessTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::CLOSED)
                    ->where('approval_status', ApprovalStatus::DISAPPROVED);
            })
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }

    public function getLatestReply(int $id)
    {
        $latestReply = Reply::where('ticket_id', $id)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return $latestReply;
    }

    public function getLatestClarification(int $id)
    {
        $latestClarification = Clarification::where('ticket_id', $id)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return $latestClarification;
    }
}