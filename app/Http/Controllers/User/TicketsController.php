<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Requester\ReplyTicketRequest;
use App\Http\Requests\Requester\StoreTicketClarificationRequest;
use App\Http\Requests\Requester\StoreTicketRequest;
use App\Http\Traits\Requester\Tickets;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Branch;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\HelpTopic;
use App\Models\LevelApprover;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketFile;
use App\Models\User;
use App\Models\UserServiceDepartment;
use App\Notifications\TicketNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

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

    public function store(StoreTicketRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $ticket = Ticket::create([
                    'user_id' => Auth::user()->id,
                    'branch_id' => $request->branch ?: Auth::user()->branch->id,
                    'service_department_id' => $request->service_department,
                    'team_id' => $request->team != 'undefined' ? $request->team : null,
                    'help_topic_id' => $request->help_topic,
                    'status_id' => Status::OPEN,
                    'priority_level_id' => $request->priority_level,
                    'sla_id' => $request->sla,
                    'ticket_number' => $this->generatedTicketNumber(),
                    'subject' => $request->subject,
                    'description' => $request->description,
                    'approval_status' => ApprovalStatus::FOR_APPROVAL,
                    'service_department_head_approver' => [
                        'id' => UserServiceDepartment::where('service_department_id', $request->service_department)->pluck('user_id')->first(),
                        'is_approved' => false
                    ]
                ]);

                if ($request->hasFile('file_attachments')) {
                    foreach ($request->file('file_attachments') as $uploadedFile) {
                        $fileName = $uploadedFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs("public/ticket/{$ticket->ticket_number}/creation_attachments", $uploadedFile, $fileName);

                        $ticketFile = new TicketFile();
                        $ticketFile->file_attachment = $fileAttachment;
                        $ticketFile->ticket_id = $ticket->id;

                        $ticket->fileAttachments()->save($ticketFile);
                    }
                }

                // Notify approvers through email and app based notification.
                $levelApprovers = LevelApprover::where('help_topic_id', $ticket->helpTopic->id)->get();
                $approvers = User::approvers();

                foreach ($ticket->helpTopic->levels as $level) {
                    foreach ($levelApprovers as $levelApprover) {
                        foreach ($approvers as $approver) {
                            if ($approver->id === $levelApprover->user_id) {
                                if ($levelApprover->level_id === $level->id) {
                                    if ($approver->buDepartments->pluck('id')->first() === $ticket->user->department_id) {
                                        Notification::send($approver, new TicketNotification($ticket, "New ticket created - $ticket->ticket_number", 'created a ticket'));
                                        // Mail::to($approver)->send(new TicketCreatedMail($ticket));
                                    }
                                }
                            }
                        }
                    }
                }

                ActivityLog::make($ticket->id, 'created a ticket');
            });

            return back()->with('success', 'A new ticket has been created');

        } catch (\Exception $e) {
            return back()->with('error', "Failed to create the ticket. Please try again.");
        }
    }

    public function viewTicket(Ticket $ticket)
    {
        return view('layouts.user.ticket.view_ticket', compact('ticket'));
    }

    public function ticketClarifications(Ticket $ticket)
    {
        return view('layouts.user.ticket.includes.ticket_clarifications', compact('ticket'));
    }

    public function loadBranches()
    {
        $branches = Branch::where('id', '!=', auth()->user()->branch_id)->get();
        return response()->json($branches);
    }

    public function loadServiceDepartments()
    {
        return response()->json(ServiceDepartment::all());
    }

    public function serviceDepartmentHelpTopics(ServiceDepartment $serviceDepartment)
    {
        return response()->json($serviceDepartment->helpTopics);
    }

    public function helpTopicTeam(HelpTopic $helpTopic)
    {
        return response()->json($helpTopic->team);
    }

    public function helpTopicSLA(HelpTopic $helpTopic)
    {
        return response()->json($helpTopic->sla);
    }
}