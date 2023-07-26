<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\Requester\Tickets;
use App\Http\Traits\TicketNumberGenerator;
use App\Models\ApprovalStatus;
use App\Models\Branch;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\ServiceDepartment;
use App\Models\Status;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\TicketFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketsController extends Controller
{
    use TicketNumberGenerator;
    use Tickets;

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

    public function store(Request $request, Ticket $ticket)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'service_department' => ['required'],
                'help_topic' => ['required'],
                'team' => ['required'],
                'sla' => ['required'],
                'subject' => ['required'],
                'description' => ['required'],
                'file_attachments.*' => ['nullable', 'mimes:jpeg,jpg,png,pdf,doc,docx,xlsx,xls,csv', 'max:30000'],
            ],
            [
                'team.required' => 'The team field is required. Please select a help topic.',
                'sla.required' => 'The SLA field is required. Please select a help topic.'
            ]
        );

        if ($validator->fails())
            return back()->withErrors($validator, 'storeTicket')->withInput();

        $ticket = Ticket::create([
            'user_id' => Auth::user()->id,
            'branch_id' => $request->input('branch') ?: Auth::user()->branch->id,
            'service_department_id' => $request->input('service_department'),
            'team_id' => $request->input('team'),
            'help_topic_id' => $request->input('help_topic'),
            'status_id' => Status::OPEN,
            'priority_level_id' => $request->input('priority_level'),
            'sla_id' => $request->input('sla'),
            'ticket_number' => $this->generatedTicketNumber(),
            'subject' => $request->input('subject'),
            'description' => $request->input('description'),
            'approval_status' => ApprovalStatus::FOR_APPROVAL
        ]);

        if ($request->hasFile('file_attachments')) {
            foreach ($request->file('file_attachments') as $uploadedFile) {
                $fileName = $uploadedFile->getClientOriginalName();
                $fileAttachment = $uploadedFile->storeAs('public/ticket/files', $fileName);

                $ticketFile = new TicketFile();
                $ticketFile->file_attachment = $fileAttachment;
                $ticketFile->ticket_id = $ticket->id;

                $ticket->fileAttachments()->save($ticketFile);
            }
        }

        return back()->with('success', 'Ticket successfully created');
    }

    public function viewTicket(int $ticketId)
    {
        $ticket = Ticket::with('replies')->findOrFail($ticketId);

        $latestReply = $this->getLatestReply($ticketId);
        $latestClarification = $this->getLatestClarification($ticketId);

        return view(
            'layouts.user.ticket.view_ticket',
            compact([
                'ticket',
                'latestReply',
                'latestClarification',
            ])
        );
    }

    public function requesterReplyTicket(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'description' => ['required'],
            'replyFiles.*' => ['nullable', 'mimes:jpeg,jpg,png,pdf,doc,docx,xlsx,xls,csv', 'max:30000']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'requesterStoreTicketReply')->withInput();

        $ticket->update(['status_id' => Status::ON_PROCESS]);

        $reply = Reply::create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'description' => $request->input('description')
        ]);

        if ($request->hasFile('replyFiles')) {
            foreach ($request->file('replyFiles') as $uploadedReplyFile) {
                $fileName = $uploadedReplyFile->getClientOriginalName();
                $fileAttachment = $uploadedReplyFile->storeAs('public/ticket/reply/files', $fileName);

                $replyFile = new ReplyFile();
                $replyFile->file_attachment = $fileAttachment;
                $replyFile->reply_id = $reply->id;

                $reply->fileAttachments()->save($replyFile);
            }
        }

        return to_route('user.ticket.view_ticket', $ticket->id)->with('success', 'Your reply has been sent successfully.');
    }

    public function ticketClarifications(int $ticketId)
    {
        $ticket = Ticket::with(['clarifications'])->findOrFail($ticketId);

        $latestReply = $this->getLatestReply($ticketId);
        $latestClarification = $this->getLatestClarification($ticketId);

        return view(
            'layouts.user.ticket.includes.ticket_clarifications',
            compact([
                'ticket',
                'latestReply',
                'latestClarification'
            ])
        );
    }

    public function sendClarification(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'description' => ['required'],
            'clarificationFiles.*' => ['nullable', 'mimes:jpeg,jpg,png,pdf,doc,docx,xlsx,xls,csv', 'max:30000']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeTicketReplyClarification')->withInput();

        $clarification = Clarification::create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'description' => $request->input('description')
        ]);

        $ticket->update([
            'status_id' => Status::ON_PROCESS
        ]);

        if ($request->hasFile('clarificationFiles')) {
            foreach ($request->file('clarificationFiles') as $uploadedClarificationFile) {
                $fileName = $uploadedClarificationFile->getClientOriginalName();
                $fileAttachment = $uploadedClarificationFile->storeAs('public/ticket/clarification/files', $fileName);

                $clarificationFile = new ClarificationFile();
                $clarificationFile->file_attachment = $fileAttachment;
                $clarificationFile->clarification_id = $clarification->id;

                $clarification->fileAttachments()->save($clarificationFile);
            }
        }

        return to_route('user.ticket.ticket_clarifications', $ticket->id)->with('success', 'Your clarification has been sent successfully.');
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