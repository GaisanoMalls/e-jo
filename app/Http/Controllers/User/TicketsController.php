<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\TicketNumberGenerator;
use App\Models\ApprovalStatus;
use App\Models\Branch;
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

    public function openTickets()
    {

        $tickets = Ticket::with(['replies', 'priorityLevel'])
                         ->where('user_id', auth()->user()->id)
                         ->orderBy('created_at', 'desc')
                         ->get();

        $openTickets = $tickets->where('status_id', Status::OPEN);

        // For ticket count purpose
        $onProcessTickets = $tickets->where('status_id', Status::ON_PROCESS);
        $closedTickets = $tickets->where('status_id', Status::CLOSED);

        return view('layouts.user.ticket.statuses.open_tickets',
            compact([
                'openTickets',
                'onProcessTickets',
                'closedTickets'
            ])
        );
    }

    public function onProcessTickets()
    {
        $tickets = Ticket::with(['replies', 'priorityLevel'])
                        ->where('user_id', auth()->user()->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        $onProcessTickets = $tickets->where('status_id', Status::ON_PROCESS);

        // For ticket count purpose
        $openTickets = $tickets->where('status_id', Status::OPEN);
        $closedTickets = $tickets->where('status_id', Status::CLOSED);

        return view('layouts.user.ticket.statuses.on_process_tickets',
            compact([
                'onProcessTickets',
                'openTickets',
                'closedTickets'
            ])
        );
    }

    public function closedTickets()
    {
        $tickets = Ticket::with(['replies', 'priorityLevel'])
                         ->where('user_id', auth()->user()->id)
                         ->orderBy('created_at', 'desc')
                         ->get();

        $closedTickets = $tickets->where('status_id', Status::CLOSED);

        // For ticket count purpose
        $openTickets = $tickets->where('status_id', Status::OPEN);
        $onProcessTickets = $tickets->where('status_id', Status::ON_PROCESS);


        return view('layouts.user.ticket.statuses.closed_tickets',
            compact([
                'closedTickets',
                'openTickets',
                'onProcessTickets',
            ])
        );
    }

    public function store(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(),
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

        if ($validator->fails()) return back()->withErrors($validator, 'storeTicket')->withInput();

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

    public function viewTicket($ticketStatusSlug, $ticketId)
    {
        $ticket = Ticket::whereHas('status', function ($query) use ($ticketStatusSlug) {
            $query->where('slug', $ticketStatusSlug);
        })->where('id', $ticketId)->first();

        $latestReply = Reply::where('ticket_id', $ticketId)
                            ->where('user_id', '!=', auth()->user()->id)
                            ->orderBy('created_at', 'desc')
                            ->first();

        return view('layouts.user.ticket.view_ticket',
            compact([
                'ticket',
                'latestReply'
            ])
        );
    }

    public function requesterReplyTicket(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'description' => ['required'],
            'replyFiles.*' => ['nullable', 'mimes:jpeg,jpg,png,pdf,doc,docx,xlsx,xls,csv', 'max:30000']
        ]);

        if ($validator->fails()) return back()->withErrors($validator, 'requesterStoreTicketReply')->withInput();

        $ticket->update(['status_id' => Status::ON_PROCESS]);

        $reply = Reply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->user()->id,
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

        return to_route('user.ticket.view_ticket', [$ticket->status->slug, $ticket->id])->with('success', 'Your reply has been sent successfully.');
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
