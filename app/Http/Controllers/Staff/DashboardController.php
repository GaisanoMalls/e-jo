<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Models\ActivityLog;
use App\Models\HelpTopic;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    use TicketsByStaffWithSameTemplates;
    public int $openTickets;
    public int $viewedTickets;
    public int $approvedTickets;
    public int $disapprovedTickets;
    public int $claimedTickets;
    public int $onProcessTickets;
    public int $overdueTickets;
    public int $closedTickets;

    public function dashboard()
    {
        $this->openTickets = $this->getOpenTickets()->count();
        $this->viewedTickets = $this->getViewedTickets()->count();
        $this->approvedTickets = $this->getApprovedTickets()->count();
        $this->disapprovedTickets = $this->getDisapprovedTickets()->count();
        $this->claimedTickets = $this->getClaimedTickets()->count();
        $this->onProcessTickets = $this->getOnProcessTickets()->count();
        $this->overdueTickets = $this->getOverdueTickets()->count();
        $this->closedTickets = $this->getClosedTickets()->count();

        $totalTickets = $this->openTickets + $this->viewedTickets + $this->approvedTickets + $this->disapprovedTickets + $this->onProcessTickets + $this->overdueTickets + $this->closedTickets;

        $ticketStatuses = [
            [
                'name' => 'Open',
                'color' => '#BEB34E',
                'count' => $this->openTickets,
                'icon' => 'fa-envelope-open-text',
                'routeName' => "staff.tickets.open_tickets"
            ],
            [
                'name' => 'Claimed',
                'color' => '#78716c',
                'count' => $this->claimedTickets,
                'icon' => 'fa-solid fa-list-check',
                'routeName' => "staff.tickets.claimed_tickets"
            ],
            [
                'name' => 'On Process',
                'color' => '#1e5e59',
                'count' => $this->onProcessTickets,
                'icon' => 'fa-gears',
                'routeName' => "staff.tickets.on_process_tickets"
            ],
            [
                'name' => 'Overdue',
                'color' => '#EA001C',
                'count' => $this->overdueTickets,
                'icon' => 'fa-triangle-exclamation',
                'routeName' => "staff.tickets.overdue_tickets"
            ],
            [
                'name' => 'Closed',
                'color' => '#4E4392',
                'count' => $this->closedTickets,
                'icon' => 'fa-envelope-circle-check',
                'routeName' => "staff.tickets.closed_tickets"
            ]
        ];

        /** @var User $currentUser */
        $currentUser = auth()->user();
        if (!$currentUser || !$currentUser->isAgent()) {
            // Additional statuses except for agents
            $additionalStatuses = [
                [
                    'name' => 'Viewed',
                    'color' => '#7ba504',
                    'count' => $this->viewedTickets,
                    'icon' => 'fa-eye',
                    'routeName' => "staff.tickets.viewed_tickets"
                ],
                [
                    'name' => 'Approved',
                    'color' => '#14532d',
                    'count' => $this->approvedTickets,
                    'icon' => 'fa-thumbs-up',
                    'routeName' => "staff.tickets.approved_tickets"
                ],
                [
                    'name' => 'Disapproved',
                    'color' => '#be123c',
                    'count' => $this->disapprovedTickets,
                    'icon' => 'fa-thumbs-down',
                    'routeName' => "staff.tickets.disapproved_tickets"
                ]
            ];

            $ticketStatuses = array_merge($ticketStatuses, $additionalStatuses);
        }

        // Ticket Activity (last 30 days)
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dateRange = collect(range(0, 29))->map(function ($i) {
            return Carbon::now()->subDays(29 - $i)->toDateString();
        });

        $createdSeries = $dateRange->map(function ($date) {
            return Ticket::whereDate('created_at', $date)->count();
        });

        $closedSeries = $dateRange->map(function ($date) {
            return ActivityLog::whereDate('created_at', $date)
                ->where('description', 'like', '%closed the ticket%')
                ->count();
        });

        $reopenedSeries = $dateRange->map(function ($date) {
            return ActivityLog::whereDate('created_at', $date)
                ->where('description', 'like', '%reopened the ticket%')
                ->count();
        });

        $assignedSeries = $dateRange->map(function ($date) {
            // Using claimed as assigned indicator
            return ActivityLog::whereDate('created_at', $date)
                ->where('description', 'like', '%claimed the ticket%')
                ->count();
        });

        $disapprovedSeries = $dateRange->map(function ($date) {
            // Using claimed as assigned indicator
            return ActivityLog::whereDate('created_at', $date)
                ->where('description', 'like', '%disapproved the ticket%')
                ->count();
        });

        $overdueSeries = $dateRange->map(function ($date) {
            return Ticket::where('status_id', Status::OVERDUE)
                ->whereDate('updated_at', $date)
                ->count();
        });

        $ticketActivity = [
            'labels' => $dateRange->map(fn($d) => Carbon::parse($d)->format('m-d')),
            'series' => [
                'created' => $createdSeries,
                'closed' => $closedSeries,
                'reopened' => $reopenedSeries,
                'assigned' => $assignedSeries,
                'disapproved' => $disapprovedSeries,
                'overdue' => $overdueSeries,
            ]
        ];

        // Statistics: Department, Topics, Agent
        $departments = ServiceDepartment::query()->get(['id','name']);
        $departmentStats = $departments->map(function ($dept) {
            $ticketQuery = Ticket::where('service_department_id', $dept->id);
            return [
                'name' => $dept->name,
                'opened' => (clone $ticketQuery)->count(),
                'assigned' => (clone $ticketQuery)->whereNotNull('agent_id')->count(),
                'overdue' => (clone $ticketQuery)->where('status_id', Status::OVERDUE)->count(),
                'closed' => (clone $ticketQuery)->where('status_id', Status::CLOSED)->count(),
                'reopened' => ActivityLog::whereIn('ticket_id', (clone $ticketQuery)->pluck('id'))
                    ->where('description', 'like', '%reopened the ticket%')->count(),
            ];
        });

        $helpTopics = HelpTopic::query()->get(['id','name']);
        $topicStats = $helpTopics->map(function ($topic) {
            $ticketQuery = Ticket::where('help_topic_id', $topic->id);
            return [
                'name' => $topic->name,
                'opened' => (clone $ticketQuery)->count(),
                'assigned' => (clone $ticketQuery)->whereNotNull('agent_id')->count(),
                'overdue' => (clone $ticketQuery)->where('status_id', Status::OVERDUE)->count(),
                'closed' => (clone $ticketQuery)->where('status_id', Status::CLOSED)->count(),
                'reopened' => ActivityLog::whereIn('ticket_id', (clone $ticketQuery)->pluck('id'))
                    ->where('description', 'like', '%reopened the ticket%')->count(),
            ];
        });

        $agents = User::role(Role::AGENT)->with('profile')->get(['id','email']);
        $agentStats = $agents->map(function ($agent) {
            $assignedQuery = Ticket::where('agent_id', $agent->id);
            return [
                'name' => $agent->profile?->getFullName ?? $agent->email,
                'opened' => 0, // agents don't open tickets
                'assigned' => (clone $assignedQuery)->count(),
                'overdue' => (clone $assignedQuery)->where('status_id', Status::OVERDUE)->count(),
                'closed' => (clone $assignedQuery)->where('status_id', Status::CLOSED)->count(),
                'reopened' => ActivityLog::whereIn('ticket_id', (clone $assignedQuery)->pluck('id'))
                    ->where('description', 'like', '%reopened the ticket%')->count(),
            ];
        });

        return view('layouts.staff.system_admin.dashboard', compact([
            'ticketStatuses',
            'totalTickets',
            'ticketActivity',
            'departmentStats',
            'topicStats',
            'agentStats',
        ]));
    }
}