<?php

namespace App\Http\Traits\Approver;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function getForApprovalTickets()
    {
        $forApprovalTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::OPEN)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('approval_status', ApprovalStatus::FOR_APPROVAL);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $forApprovalTickets;
    }

    public function getOpenTickets()
    {
        $openTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::OPEN)
                ->where('branch_id', auth()->user()->branch_id);
        })
            ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL])
            ->orderBy('created_at', 'desc')
            ->get();

        return $openTickets;
    }

    public function getViewedTickets()
    {
        $viewedTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::VIEWED)
                ->where('branch_id', auth()->user()->branch_id);
        })
            ->whereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::FOR_APPROVAL])
            ->orderBy('created_at', 'desc')
            ->get();

        return $viewedTickets;
    }

    public function getApprovedTickets()
    {
        $approvedTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::APPROVED)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->where('branch_id', auth()->user()->branch_id);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $approvedTickets;
    }

    public function getDisapprovedTickets()
    {
        $disapprovedTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::CLOSED)
                ->where('approval_status', ApprovalStatus::DISAPPROVED)
                ->where('branch_id', auth()->user()->branch_id);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $disapprovedTickets;
    }

    public function getOnHoldTickets()
    {
        $onHoldTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::ON_HOLD)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->where('branch_id', auth()->user()->branch_id);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $onHoldTickets;
    }
}