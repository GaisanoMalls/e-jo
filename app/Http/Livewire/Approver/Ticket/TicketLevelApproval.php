<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Mail\Staff\ApprovedTicketMail;
use App\Models\ActivityLog;
use App\Models\Role;
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

    /**
     * Retrieves approvers for a specific approval level based on ticket context.
     *
     * Fetches users with approver profiles who are configured to approve tickets:
     * - At the specified approval level
     * - For the ticket's help topic
     * - Within the ticket requester's BU departments and branches
     *
     * The query includes eager loading of user profiles and nested relationships
     * for efficient data retrieval.
     *
     * @param int $level The approval level to filter by (e.g., 1 for first-level approval)
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of User models with:
     *         - Profile data loaded
     *         - Help topic approval configurations
     *         - Filtered by the ticket's context
     *         Returns empty collection if no matching approvers found
     *
     * @throws \Exception If ticket user relationships are not properly loaded
     *
     * @uses \App\Models\User The base user model
     * @uses \App\Models\HelpTopicApproval For approval level configuration
     */
    public function fetchApprovers(int $level)
    {
        return User::with('profile')
            ->withWhereHas('helpTopicApprovals', function ($query) use ($level) {
                $query->where('level', $level)
                    ->withWhereHas('configuration', function ($config) {
                        $config->with('approvers')
                            ->where('help_topic_id', $this->ticket->help_topic_id)
                            ->whereIn('bu_department_id', $this->ticket->user?->buDepartments->pluck('id'));
                    });
            })->get();
    }

    /**
     * Checks if the current ticket has been approved.
     *
     * Verifies whether there exists any approved ticket approval record that:
     * 1. Belongs to the current ticket
     * 2. Is marked as approved (is_approved = true)
     * 3. Is associated with the ticket's help topic approver
     *
     * @return bool Returns true if an approved ticket approval exists for:
     *              - The current ticket
     *              - The ticket's help topic
     *             Returns false otherwise
     *
     * @uses \App\Models\TicketApproval For approval records
     * @uses withWhereHas For efficient relationship filtering
     */
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

    /**
     * Checks if the ticket has been approved at a specific approval level.
     *
     * Verifies whether there exists an approved ticket approval record that:
     * 1. Belongs to the current ticket
     * 2. Is marked as approved (is_approved = true)
     * 3. Is associated with an approver configured for:
     *    - The specified approval level
     *    - The ticket's help topic
     *
     * @param int $level The approval level to check (e.g., 1, 2, etc.)
     * @return bool Returns true if an approved ticket approval exists for:
     *              - The current ticket
     *              - The specified approval level
     *              - The ticket's help topic
     *             Returns false otherwise
     *
     * @uses \App\Models\TicketApproval For approval records
     * @uses withWhereHas For efficient relationship filtering
     */
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

    /**
     * Handles post-submission actions for ticket approval.
     * 
     * Performs three key actions after form submission:
     * 1. Emits event to refresh level of approval data
     * 2. Emits event to refresh ticket logs
     * 3. Redirects to the approved tickets route
     *
     * @return void
     * @fires loadLevelOfApproval To refresh approval level data
     * @fires loadTicketLogs To refresh ticket logs
     * @redirects approver.tickets.approved After successful submission
     */
    private function actionOnSubmit()
    {
        $this->emit('loadLevelOfApproval');
        $this->emit('loadTicketLogs');
        $this->redirectRoute('approver.tickets.approved');
    }

    /**
     * Approves a ticket and notifies relevant agents.
     *
     * Handles the ticket approval workflow by:
     * 1. Verifying the user has approver permissions
     * 2. In a database transaction:
     *    - Finds all agents in the ticket's branch and service department
     *    - Sends email and in-app notifications to each agent
     * 3. Logs the approval activity
     * 4. Performs post-approval actions (refresh and redirect)
     * 5. Handles errors gracefully with logging
     *
     * @return void
     * @throws \Exception On database or notification errors (handled internally)
     *
     * @uses \App\Models\User For agent lookup
     * @uses \App\Mail\ApprovedTicketMail For email notifications
     * @uses \App\Notifications\AppNotification For in-app notifications
     * @uses \App\Models\ActivityLog For activity tracking
     * @uses noty() For user feedback
     *
     * @fires actionOnSubmit After successful approval
     * @emits warning notification Via noty() if unauthorized
     */
    public function approveTicket()
    {
        try {
            if (auth()->user()->isApprover()) {
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
                noty()->addWarning('You hamessage: ve no rights/permission to approve the ticket.');
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
