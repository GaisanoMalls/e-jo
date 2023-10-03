<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\Requester\Tickets;
use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\HelpTopic;
use App\Models\ServiceDepartment;
use App\Models\Ticket;

class TicketsController extends Controller
{
    use Utils, Tickets;

    public function openTickets()
    {
        return view('layouts.user.ticket.statuses.open_tickets');
    }

    public function viewedTickets()
    {
        return view('layouts.user.ticket.statuses.viewed_tickets');
    }

    public function onProcessTickets()
    {
        return view('layouts.user.ticket.statuses.on_process_tickets');
    }

    public function approvedTickets()
    {
        return view('layouts.user.ticket.statuses.approved_tickets');
    }

    public function claimedTickets()
    {
        return view('layouts.user.ticket.statuses.claimed_tickets');
    }

    public function disapprovedTickets()
    {
        return view('layouts.user.ticket.statuses.disapproved_tickets');
    }

    public function closedTickets()
    {
        return view('layouts.user.ticket.statuses.closed_tickets');
    }

    public function viewTicket(Ticket $ticket)
    {
        return view('layouts.user.ticket.view_ticket', compact('ticket'));
    }

    public function ticketClarifications(Ticket $ticket)
    {
        return view('layouts.user.ticket.includes.ticket_clarifications', compact('ticket'));
    }
}