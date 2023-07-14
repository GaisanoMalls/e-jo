<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\TicketNumberGenerator;
use App\Models\ApprovalStatus;
use App\Models\Branch;
use App\Models\HelpTopic;
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

        $tickets = Ticket::where('user_id', auth()->user()->id)
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
        $tickets = Ticket::where('user_id', auth()->user()->id)
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
        $tickets = Ticket::where('user_id', auth()->user()->id)
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
        $validator = Validator::make($request->all(), [
            'service_department' => ['required'],
            'help_topic' => ['required'],
            'subject' => ['required'],
            'description' => ['required'],
            'file_attachments.*' => ['nullable', 'mimes:jpeg,jpg,png,pdf,docx', 'max:30000'],
        ]);

        if ($validator->fails()) return back()->withErrors($validator, 'storeTicket')->withInput();

        $ticket = Ticket::create([
            'user_id' => (int) Auth::user()->id,
            'branch_id' => (int) $request->input('branch') ?: (int) Auth::user()->branch->id,
            'service_department_id' => (int) $request->input('service_department'),
            'team_id' => (int) $request->input('team'),
            'help_topic_id' => (int) $request->input('help_topic'),
            'status_id' => Status::OPEN,
            'priority_level_id' => (int) $request->input('priority_level'),
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

    public function loadBranches()
    {
        $branches = Branch::where('id', '!=', auth()->user()->branch_id)->get();
        return response()->json($branches);
    }

    public function loadServiceDepartmentsByUserBranch(User $user)
    {
        $serviceDepartments = ServiceDepartment::whereHas('branches', function ($query) use ($user) {
            $query->where('branches.id', $user->branch_id);
        })->get();

        return response()->json($serviceDepartments);
    }

    public function serviceDepartmentHelpTopics(ServiceDepartment $serviceDepartment)
    {
        return response()->json($serviceDepartment->helpTopics);
    }

    public function helpTopicTeam(HelpTopic $helpTopic)
    {
        return response()->json($helpTopic->team);
    }
}
