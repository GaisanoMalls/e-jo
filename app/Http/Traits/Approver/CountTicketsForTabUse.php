<?php

namespace App\Http\Traits\Approver;
use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait CountTicketsForTabUse {

    public function countOpenTickets() {
        $openTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::OPEN)
            ->where('branch_id', auth()->user()->branch_id);
        })
        ->orWhereIn('approval_status', [ApprovalStatus::APPROVED, ApprovalStatus::DISAPPROVED])
        ->orderBy('created_at', 'desc')
        ->get();

        return $openTickets;
    }
}