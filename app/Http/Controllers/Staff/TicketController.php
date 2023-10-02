<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Reply;
use App\Models\Ticket;
use App\Models\User;

class TicketController extends Controller
{
    use TicketsByStaffWithSameTemplates, Utils, BasicModelQueries;

    public function ticketsToAssign()
    {
        return view('layouts.staff.ticket.tickets_to_assign');

    }

    public function approvedTickets()
    {
        $approvedTickets = $this->getApprovedTickets();
        return view('layouts.staff.ticket.statuses.approved_tickets', compact('approvedTickets'));
    }

    public function disapprovedTickets()
    {
        $disapprovedTickets = $this->getDisapprovedTickets();
        return view('layouts.staff.ticket.statuses.disapproved_tickets', compact('disapprovedTickets'));
    }

    public function openTickets()
    {
        $openTickets = $this->getOpenTickets();
        return view('layouts.staff.ticket.statuses.open_tickets', compact('openTickets'));
    }

    public function onProcessTickets()
    {
        $onProcessTickets = $this->getOnProcessTickets();
        return view('layouts.staff.ticket.statuses.on_process_tickets', compact('onProcessTickets'));
    }

    public function claimedTickets()
    {
        $claimedTickets = $this->getClaimedTickets();
        return view('layouts.staff.ticket.statuses.claimed_tickets', compact('claimedTickets'));
    }

    public function viewedTickets()
    {
        $viewedTickets = $this->getViewedTickets();
        return view('layouts.staff.ticket.statuses.viewed_tickets', compact('viewedTickets'));
    }

    public function reopenedTickets()
    {
        return view('layouts.staff.ticket.statuses.reopened_tickets');
    }

    public function overdueTickets()
    {
        $overdueTickets = $this->getOverdueTickets();
        return view('layouts.staff.ticket.statuses.overdue_tickets', compact('overdueTickets'));
    }

    public function closedTickets()
    {
        $closedTickets = $this->getClosedTickets();
        return view('layouts.staff.ticket.statuses.closed_tickets', compact('closedTickets'));
    }

    public function viewTicket(Ticket $ticket)
    {
        $teams = $this->queryTeams();
        $departments = $this->queryBUDepartments();
        $priorityLevels = $this->queryPriorityLevels();
        $serviceDepartments = $this->queryServiceDepartments();
        $approvers = User::whereHas('teams', function ($query) use ($ticket) {
            $query->where('teams.id', $ticket->team_id);
        })
            ->where('users.branch_id', $ticket->branch_id)
            ->where('users.service_department_id', $ticket->service_department_id)
            ->where('id', '!=', $ticket->agent_id)
            ->get();

        $latestReply = Reply::where('ticket_id', $ticket->id)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return view(
            'layouts.staff.ticket.view_ticket',
            compact([
                'ticket',
                'departments',
                'serviceDepartments',
                'latestReply',
                'priorityLevels',
                'teams',
                'approvers'
            ])
        );
    }

    public function ticketActionGetDepartmentServiceDepartments(Department $department)
    {
        return response()->json($department->teams);
    }
}