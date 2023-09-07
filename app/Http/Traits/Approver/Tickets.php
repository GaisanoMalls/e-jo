<?php

namespace App\Http\Traits\Approver;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function getForApprovalTickets()
    {
        $forApprovalTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::FOR_APPROVAL);
        })
            ->withWhereHas('helpTopic.levels.approvers', function ($approverQuery) {
                $approverQuery->where('users.id', auth()->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $forApprovalTickets;
    }

    public function getDisapprovedTickets()
    {
        $disapprovedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::DISAPPROVED);
        })
            ->withWhereHas('helpTopic.levels.approvers', function ($approverQuery) {
                $approverQuery->where('users.id', auth()->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $disapprovedTickets;
    }

    public function getOpenTickets()
    {
        $openTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->withWhereHas('helpTopic.levels.approvers', function ($approverQuery) {
                $approverQuery->where('users.id', auth()->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $openTickets;
    }

    public function getViewedTickets()
    {
        $viewedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->withWhereHas('helpTopic.levels.approvers', function ($approverQuery) {
                $approverQuery->where('users.id', auth()->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $viewedTickets;
    }

    public function getApprovedTickets()
    {
        $approvedTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED);
        })
            ->withWhereHas('helpTopic.levels.approvers', function ($approverQuery) {
                $approverQuery->where('users.id', auth()->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $approvedTickets;
    }

    public function getOnProcessTickets()
    {
        $onProcessTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL]);
        })
            ->whereHas('helpTopic.levels.approvers', function ($approverQuery) {
                $approverQuery->where('users.id', auth()->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }
}