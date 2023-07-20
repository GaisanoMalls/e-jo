<?php

namespace App\Http\Traits\Requester;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function getOpenTickets()
    {
        $openTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->where('status_id', Status::OPEN);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $openTickets;
    }

    public function getOnProcessTickets()
    {
        $onProcessTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->where('status_id', Status::ON_PROCESS)
                    ->where('approval_status', ApprovalStatus::APPROVED);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }

    public function getViewedTickets()
    {
        $viewedTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->where('status_id', Status::VIEWED);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $viewedTickets;
    }

    public function getApprovedTickets()
    {
        $approvedTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->where('status_id', Status::APPROVED)
                    ->where('approval_status', ApprovalStatus::APPROVED);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $approvedTickets;
    }

    public function getClosedTickets()
    {
        $onProcessTickets = Ticket::with(['replies', 'priorityLevel'])
            ->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->where('status_id', Status::CLOSED)
                    ->where('approval_status', ApprovalStatus::DISAPPROVED);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }
}