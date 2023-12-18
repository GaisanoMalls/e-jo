<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Traits\Approver\Tickets as ApproverTickets;
use App\Http\Traits\Utils;
use App\Models\Clarification;
use App\Models\Ticket;

class ApproverTicketsController extends Controller
{
    use ApproverTickets, Utils;

    public function openTickets()
    {
        return view('layouts.staff.approver.ticket.statuses.open');
    }

    public function viewedTickets()
    {
        return view('layouts.staff.approver.ticket.statuses.viewed');
    }

    public function approvedTickets()
    {
        return view('layouts.staff.approver.ticket.statuses.approved');
    }

    public function disapprovedTickets()
    {
        return view('layouts.staff.approver.ticket.statuses.disapproved');
    }

    public function onProcessTickets()
    {
        return view('layouts.staff.approver.ticket.statuses.on_process');
    }

    public function viewTicketDetails(Ticket $ticket)
    {
        $latestClarification = Clarification::whereHas('ticket', fn($query) => $query->where('ticket_id', $ticket->id))
            ->whereHas('user', fn($user) => $user->where('user_id', '!=', auth()->user()->id))
            ->orderByDesc('created_at')
            ->first();

        return view('layouts.staff.approver.ticket.view_ticket',
            compact([
                'ticket',
                'latestClarification',
            ])
        );
    }
}