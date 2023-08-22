<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Requester\ReplyTicketRequest;
use App\Http\Requests\Requester\StoreTicketClarificationRequest;
use App\Http\Requests\Requester\StoreTicketRequest;
use App\Http\Traits\FileUploadDir;
use App\Http\Traits\Requester\Tickets;
use App\Http\Traits\TicketNumberGenerator;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Branch;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\HelpTopic;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketFile;
use App\Notifications\TicketCreatedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class TicketsController extends Controller
{
    use TicketNumberGenerator, Tickets, FileUploadDir;

    public function openTickets()
    {
        // For ticket count purpose
        $onProcessTickets = $this->getOnProcessTickets();
        $closedTickets = $this->getClosedTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();

        $openTickets = $this->getOpenTickets();


        return view(
            'layouts.user.ticket.statuses.open_tickets',
            compact([
                'onProcessTickets',
                'closedTickets',
                'viewedTickets',
                'approvedTickets',
                'disapprovedTickets',
                'openTickets',
            ])
        );
    }

    public function onProcessTickets()
    {
        // For ticket count purpose
        $openTickets = $this->getOpenTickets();
        $closedTickets = $this->getClosedTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();

        $onProcessTickets = $this->getOnProcessTickets();

        return view(
            'layouts.user.ticket.statuses.on_process_tickets',
            compact([
                'openTickets',
                'closedTickets',
                'viewedTickets',
                'approvedTickets',
                'disapprovedTickets',
                'onProcessTickets',
            ])
        );
    }

    public function viewedTickets()
    {
        // For ticket count purpose
        $openTickets = $this->getOpenTickets();
        $onProcessTickets = $this->getOnProcessTickets();
        $closedTickets = $this->getClosedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();

        $viewedTickets = $this->getViewedTickets();

        return view(
            'layouts.user.ticket.statuses.viewed_tickets',
            compact([
                'openTickets',
                'onProcessTickets',
                'closedTickets',
                'approvedTickets',
                'disapprovedTickets',
                'viewedTickets',
            ])
        );
    }

    public function approvedTickets()
    {
        // For ticket count purpose
        $openTickets = $this->getOpenTickets();
        $onProcessTickets = $this->getOnProcessTickets();
        $closedTickets = $this->getClosedTickets();
        $viewedTickets = $this->getViewedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();

        $approvedTickets = $this->getApprovedTickets();


        return view(
            'layouts.user.ticket.statuses.approved_tickets',
            compact([
                'openTickets',
                'onProcessTickets',
                'closedTickets',
                'viewedTickets',
                'disapprovedTickets',
                'approvedTickets',
            ])
        );
    }

    public function disapprovedTickets()
    {
        // For ticket count purpose
        $openTickets = $this->getOpenTickets();
        $onProcessTickets = $this->getOnProcessTickets();
        $closedTickets = $this->getClosedTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();

        $disapprovedTickets = $this->getDisapprovedTickets();

        return view(
            'layouts.user.ticket.statuses.disapproved_tickets',
            compact([
                'openTickets',
                'onProcessTickets',
                'closedTickets',
                'viewedTickets',
                'approvedTickets',
                'disapprovedTickets',
            ])
        );
    }

    public function closedTickets()
    {
        // For ticket count purpose
        $openTickets = $this->getOpenTickets();
        $onProcessTickets = $this->getOnProcessTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();

        $closedTickets = $this->getClosedTickets();

        return view(
            'layouts.user.ticket.statuses.closed_tickets',
            compact([
                'openTickets',
                'onProcessTickets',
                'viewedTickets',
                'approvedTickets',
                'disapprovedTickets',
                'closedTickets',
            ])
        );
    }

    public function store(StoreTicketRequest $request, Ticket $ticket)
    {
        try {
            DB::transaction(function () use ($request) {
                $ticket = Ticket::create([
                    'user_id' => Auth::user()->id,
                    'branch_id' => $request->branch ?: Auth::user()->branch->id,
                    'service_department_id' => $request->service_department,
                    'team_id' => $request->team,
                    'help_topic_id' => $request->help_topic,
                    'status_id' => Status::OPEN,
                    'priority_level_id' => $request->priority_level,
                    'sla_id' => $request->sla,
                    'ticket_number' => $this->generatedTicketNumber(),
                    'subject' => $request->subject,
                    'description' => $request->description,
                    'approval_status' => ApprovalStatus::FOR_APPROVAL
                ]);

                if ($request->hasFile('file_attachments')) {
                    foreach ($request->file('file_attachments') as $uploadedFile) {
                        $fileName = $uploadedFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$ticket->ticket_number}/creation_attachments",
                            $uploadedFile,
                            $fileName
                        );

                        $ticketFile = new TicketFile();
                        $ticketFile->file_attachment = $fileAttachment;
                        $ticketFile->ticket_id = $ticket->id;

                        $ticket->fileAttachments()->save($ticketFile);
                    }
                }

                // Notify approvers through email and app based notification.
                foreach ($ticket->helpTopic->levels as $level) {
                    foreach ($level->approvers->unique('id') as $approver) {
                        Notification::send($approver, new TicketCreatedNotification($ticket));
                    }
                }

                ActivityLog::make($ticket->id, auth()->user()->id, 'created a ticket');
            });

            return back()->with('success', 'Ticket successfully created.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', "Failed to send ticket. Please try again");
        }
    }

    public function viewTicket(Ticket $ticket)
    {
        $latestReply = $this->getLatestReply($ticket->id);
        $latestClarification = $this->getLatestClarification($ticket->id);
        $reason = $ticket->reasons()->where('ticket_id', $ticket->id)->first();

        return view(
            'layouts.user.ticket.view_ticket',
            compact([
                'ticket',
                'latestReply',
                'latestClarification',
                'reason'
            ])
        );
    }

    public function requesterReplyTicket(ReplyTicketRequest $request, Ticket $ticket)
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
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$ticket->ticket_number}/reply_attachments/" . $this->fileDirByUserType(),
                            $uploadedReplyFile,
                            $fileName
                        );

                        $replyFile = new ReplyFile();
                        $replyFile->file_attachment = $fileAttachment;
                        $replyFile->reply_id = $reply->id;

                        $reply->fileAttachments()->save($replyFile);
                    }
                }

                $latestReply = Reply::where('ticket_id', $ticket->id)
                    ->whereHas('user', function ($user) {
                        $user->where('role_id', '!=', Role::USER);
                    })
                    ->latest('created_at')
                    ->first();

                ActivityLog::make(
                    $ticket->id,
                    auth()->user()->id,
                    'replied to ' . $latestReply->user->profile->getFullName()
                );
            });

            return back()->with('success', 'Your reply has been sent successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send your reply. Please try again.');
        }
    }

    public function ticketClarifications(Ticket $ticket)
    {
        $latestReply = $this->getLatestReply($ticket->id);
        $latestClarification = $this->getLatestClarification($ticket->id);
        $reason = $ticket->reasons()->where('ticket_id', $ticket->id)->first();

        return view(
            'layouts.user.ticket.includes.ticket_clarifications',
            compact([
                'ticket',
                'latestReply',
                'latestClarification',
                'reason'
            ])
        );
    }

    public function sendClarification(StoreTicketClarificationRequest $request, Ticket $ticket)
    {
        try {
            DB::transaction(function () use ($request, $ticket) {
                $ticket->update(['status_id' => Status::ON_PROCESS]);

                $clarification = Clarification::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $ticket->id,
                    'description' => $request->description
                ]);

                if ($request->hasFile('clarificationFiles')) {
                    foreach ($request->file('clarificationFiles') as $uploadedClarificationFile) {
                        $fileName = $uploadedClarificationFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$ticket->ticket_number}/clarification_attachments/" . $this->fileDirByUserType(),
                            $uploadedClarificationFile,
                            $fileName
                        );

                        $clarificationFile = new ClarificationFile();
                        $clarificationFile->file_attachment = $fileAttachment;
                        $clarificationFile->clarification_id = $clarification->id;

                        $clarification->fileAttachments()->save($clarificationFile);
                    }
                }

                // * GET THE LATEST STAFF
                $latestStaff = $clarification->whereHas('user', function ($user) {
                    $user->where('id', '!=', auth()->user()->id);
                })
                    ->where('ticket_id', $ticket->id)
                    ->latest('created_at')
                    ->first();

                // * CONSTRUCT A LOG DESCRIPTION
                $logClarificationDescription = $ticket->clarifications()
                    ->where('user_id', '!=', auth()->user()->id)->count() === 0
                    ? 'sent a clarification'
                    : 'replied a clarification to ' . $latestStaff->user->profile->getFullName();

                ActivityLog::make($ticket->id, auth()->user()->id, $logClarificationDescription);

            });

            return back()->with('success', 'Your clarification has been sent successfully.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to send ticket clarification. Please try again.');
        }
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