<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Http\Traits\Utils;
use App\Models\PriorityLevel;
use App\Models\Reply;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;

class TicketController extends Controller
{
    use TicketsByStaffWithSameTemplates, Utils, BasicModelQueries;

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
        return view('layouts.staff.ticket.statuses.open_tickets');
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

    public function myBookmarkedTickets()
    {
        $bookmarkedTickets = Ticket::whereHas('bookmark', fn($query) => $query->where('bookmarks.user_id', auth()->user()->id))->get();
        return view('layouts.staff.bookmark.my_bookmarks', compact('bookmarkedTickets'));
    }

    public function viewTicket(Ticket $ticket)
    {
        // Gate::authorize('canViewTicket', $ticket);
        $teams = $this->queryTeams();
        $departments = $this->queryBUDepartments();
        $priorityLevels = $this->queryPriorityLevels();
        $serviceDepartments = $this->queryServiceDepartments();

        $approvers = User::whereHas('teams', fn($query) => $query->where('teams.id', $ticket->team_id))
            ->whereHas('branches', fn($query) => $query->where('branches.id', $ticket->branch_id))
            ->whereHas('serviceDepartments', fn($query) => $query->where('service_departments.id', $ticket->service_department_id))
            ->where('id', '!=', $ticket->agent_id)
            ->get();

        $latestReply = Reply::where('ticket_id', $ticket->id)->where('user_id', '!=', auth()->user()->id)->orderByDesc('created_at')->first();
        $requester = $ticket->user()->with('profile')->withTrashed()->first();

        return view('layouts.staff.ticket.view_ticket', compact([
            'ticket',
            'departments',
            'serviceDepartments',
            'latestReply',
            'priorityLevels',
            'teams',
            'approvers',
            'requester'
        ]));
    }

    // Query ticket by priotity level
    public function queryTicketByPriorityLevel(PriorityLevel $priorityLevel)
    {
        $currentUser = User::find(auth()->user()->id);

        if ($currentUser->hasRole(Role::SERVICE_DEPARTMENT_ADMIN)) {
            $tickets = Ticket::whereNot('status_id', Status::CLOSED)
                ->whereHas('user', function ($user) {
                    $user->withTrashed()
                        ->whereHas('branches', function ($branch) {
                            $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                        })
                        ->whereHas('buDepartments', function ($department) {
                            $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                        })
                        ->orWhereHas('tickets', function ($ticket) {
                            $ticket->where('branch_id', auth()->user()->branches->pluck('id')->toArray())
                                ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
                        });
                })
                ->orderByDesc('created_at')
                ->get();
        } else if ($currentUser->hasRole(Role::SYSTEM_ADMIN)) {
            $tickets = Ticket::whereNot('status_id', Status::CLOSED)
                ->where('priority_level_id', $priorityLevel->id)
                ->get();
        }

        return view('layouts.staff.ticket.priority_level_tickets', compact(['tickets', 'priorityLevel']));
    }
}