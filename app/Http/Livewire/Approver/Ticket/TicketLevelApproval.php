<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Mail\Staff\ApprovedTicketMail;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    use Utils;

    public Ticket $ticket;
    public Collection $approvers;
    public Collection $ticketApprovals;
    public array $approvalLevels = [1, 2, 3, 4, 5];

    protected $listeners = ['loadLevelOfApproval' => '$refresh'];

    public function mount()
    {
        $this->ticketApprovals = TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->whereIn('level', $this->approvalLevels);
            })->get();
    }

    public function fetchApprovers(int $level)
    {
        return User::with('profile')
            ->withWhereHas('helpTopicApprovals', function ($query) use ($level) {
                $query->where('level', $level)
                    ->withWhereHas('configuration', function ($config) {
                        $config->with('approvers')
                            ->where('help_topic_id', $this->ticket->help_topic_id)
                            ->whereIn('bu_department_id', $this->ticket->user?->buDepartments->pluck('id')->toArray());
                    });
            })->get();
    }

    public function isApprovalApproved()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true],
        ])
            ->withWhereHas('helpTopicApprover', fn($approver)
                => $approver->where('help_topic_id', $this->ticket->help_topic_id))
            ->exists();
    }

    public function islevelApproved(int $level)
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true],
        ])
            ->withWhereHas('helpTopicApprover', fn($approver) =>
                $approver->where([
                    ['level', $level],
                    ['help_topic_id', $this->ticket->help_topic_id],
                ]))
            ->exists();
    }

    private function actionOnSubmit()
    {
        $this->emit('loadLevelOfApproval');
        $this->emit('loadTicketLogs');
        $this->redirectRoute('approver.tickets.approved');
    }

    public function approveTicket()
    {
        try {
            if (auth()->user()->hasRole(Role::APPROVER)) {
                DB::transaction(function () {
                    // Get the agents.
                    $agents = User::role(Role::AGENT)
                        ->withWhereHas('branches', fn($query) => $query->whereIn('branches.id', [$this->ticket->branch_id]))
                        ->withWhereHas('serviceDepartments', fn($query) => $query->whereIn('service_departments.id', [$this->ticket->service_department_id]))
                        ->get();

                    // Notify agents through email and app based notification.
                    $agents->each(function ($agent) {
                        Mail::to($agent)->send(new ApprovedTicketMail($this->ticket, $agent));
                        Notification::send(
                            $agent,
                            new AppNotification(
                                ticket: $this->ticket,
                                title: "Ticket #{$this->ticket->ticket_number} (New)",
                                message: "You have a new ticket",
                            )
                        );
                    });
                });

                ActivityLog::make(ticket_id: $this->ticket->id, description: 'approved the level 2 approval');
                $this->actionOnSubmit();
            } else {
                noty()->addError('You have no rights/permission to approve the ticket');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.approver.ticket.ticket-level-approval');
    }
}
