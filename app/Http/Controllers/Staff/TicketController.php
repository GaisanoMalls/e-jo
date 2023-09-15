<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffReplyTicketRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function replyTicket(StaffReplyTicketRequest $request, Ticket $ticket)
    {
        try {
            DB::transaction(function () use ($request, $ticket) {
                $ticket->update(['status_id' => Status::ON_PROCESS]);

                $reply = Reply::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $ticket->id,
                    'description' => $request->description
                ]);

                if ($request->hasFile('replyFiles')) {
                    foreach ($request->file('replyFiles') as $uploadedReplyFile) {
                        $fileName = $uploadedReplyFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs("public/ticket/{$ticket->ticket_number}/reply_attachments/" . $this->fileDirByUserType(), $uploadedReplyFile, $fileName);

                        $replyFile = new ReplyFile();
                        $replyFile->file_attachment = $fileAttachment;
                        $replyFile->reply_id = $reply->id;

                        $reply->fileAttachments()->save($replyFile);
                    }
                }

                ActivityLog::make($ticket->id, "replied to {$ticket->user->profile->getFullName()}");
            });

            return back()->with('success', 'Ticket reply has been sent.');

        } catch (\Exception $e) {
            return back()->with('error', 'Faild to send a reply for this ticket. Please try again.');
        }
    }

    public function ticketActionGetDepartmentServiceDepartments(Department $department)
    {
        return response()->json($department->teams);
    }
}