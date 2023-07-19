<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Traits\Approver\CountTicketsForTabUse;
use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ApproverTicketsController extends Controller
{
    use CountTicketsForTabUse;

    public function openTickets()
    {
        $openTickets = $this->countOpenTickets();

        $forApprovalTickets = Ticket::where(function ($query) {
                                    $query->where('status_id', Status::OPEN)
                                        ->where('branch_id', auth()->user()->branch_id)
                                        ->where('approval_status', ApprovalStatus::FOR_APPROVAL);
                                })
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('layouts.staff.approver.tickets.open', compact('openTickets', 'forApprovalTickets'));
    }

    public function approvedTickets()
    {
        $openTickets = $this->countOpenTickets();

        $approvedTickets = Ticket::where('status_id', Status::OPEN)
                                 ->where('approval_status', ApprovalStatus::APPROVED)
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        return view('layouts.staff.approver.tickets.approved', compact('approvedTickets', 'openTickets'));
    }

    public function disapprovedTickets()
    {
        $disapprovedTickets = Ticket::where('status_id', Status::OPEN)
                                    ->where('approval_status', ApprovalStatus::DISAPPROVED)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        return view('layouts.staff.approver.tickets.disapproved', compact('disapprovedTickets'));
    }

    public function approveTicket(Ticket $ticket)
    {
        $ticket->update([
            'approval_status' => ApprovalStatus::APPROVED
        ]);

        return back()->with('success', 'Ticket was successfully approved.');
    }

    public function approveAllTickets(Ticket $ticket)
    {
        // $ticket->
    }

    public function disapproveTicket(Ticket $ticket)
    {
        $ticket->update([
            'approval_status' => ApprovalStatus::DISAPPROVED
        ]);

        return back()->with('info', 'Ticket is rejected.');
    }
}
