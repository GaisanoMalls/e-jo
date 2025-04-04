<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\TicketApprovalLevel;
use App\Mail\Staff\ApprovedTicketMail;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ApproveTicket extends Component
{
    use TicketApprovalLevel;

    public Ticket $ticket;

    /**
     * Emits a series of predefined Livewire events to update ticket-related components.
     *
     * This function broadcasts multiple events to ensure that various parts of the ticket
     * interface are updated in real-time. It iterates over a predefined list of event names
     * and emits each event to its corresponding Livewire listener.
     *
     * Events emitted:
     * - 'loadTicketLogs': Updates the ticket logs component.
     * - 'loadTicketDetails': Updates the ticket details component.
     * - 'loadLevelOfApproval': Updates the level of approval component.
     * - 'loadTicketStatusHeaderText': Updates the ticket status header text.
     * - 'loadDropdownApprovalButton': Updates the dropdown approval button.
     * - 'remountRequesterCustomForm': Remounts the requester's custom form.
     *
     * @return void
     */
    private function triggerEvents()
    {
        $events = [
            'loadTicketLogs',
            'loadTicketDetails',
            'loadLevelOfApproval',
            'loadTicketStatusHeaderText',
            'loadDropdownApprovalButton',
            'remountRequesterCustomForm'
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    /**
     * Handles post-submission actions for ticket approval.
     *
     * This function performs two key actions after a ticket approval process:
     * 1. Emits predefined Livewire events to update ticket-related components in real-time.
     *    These events ensure that the ticket logs, details, approval levels, and other UI elements
     *    are refreshed to reflect the latest changes.
     * 2. Dispatches a browser event to close the modal window, providing a seamless user experience
     *    after the ticket approval process is completed.
     *
     * @return void
     */
    private function actionOnSubmit()
    {
        $this->triggerEvents();
        $this->dispatchBrowserEvent('close-modal');
    }

    /**
     * Checks if the currently logged-in user is an approver for the current level of the ticket.
     *
     * This function determines whether the logged-in user is listed as an approver for the
     * current help topic associated with the ticket. It queries the approvers of the help topic
     * to check if the user's ID exists in the list of approvers.
     *
     * @return bool Returns true if the user is an approver for the current level, otherwise false.
     */
    public function isCurrentLevelApprover()
    {
        return $this->ticket->helpTopic->approvers()->where('user_id', auth()->user()->id)->exists();
    }

    /**
     * Approves the current ticket if the logged-in user has the necessary permissions.
     *
     * This function handles the ticket approval process for the currently logged-in user. It performs the following steps:
     * 1. Checks if the user is an approver and is authorized to approve the current level of the ticket.
     * 2. Executes a database transaction to:
     *    - Verify that the ticket has not already been approved.
     *    - Approve the current level of the ticket.
     *    - Notify agents associated with the ticket via email and in-app notifications.
     *    - Update the ticket's custom form footer with the approver's ID.
     *    - Log the approval activity.
     * 3. Emits Livewire events to update ticket-related components and closes the modal window.
     * 4. Provides feedback to the user if the ticket has already been approved or if the user lacks the necessary permissions.
     *
     * @return void
     * @throws Exception If an error occurs during the database transaction or notification process.
     */
    public function approveTicket()
    {
        try {
            if (auth()->user()->isApprover() && $this->isCurrentLevelApprover()) {
                DB::transaction(function () {
                    if ($this->ticket->status_id != Status::APPROVED && $this->ticket->approval_status != ApprovalStatusEnum::APPROVED) {
                        $approvedLevel = $this->approveLevelOfApproval($this->ticket);

                        if ($approvedLevel) {
                            $agents = User::with('profile')
                                ->withWhereHas('teams', function ($team) {
                                    $team->whereIn('teams.id', $this->ticket->teams->pluck('id'));
                                })
                                ->withWhereHas('serviceDepartments', function ($serviceDepartment) {
                                    $serviceDepartment->where('service_departments.id', $this->ticket->service_department_id);
                                })
                                ->role(Role::AGENT)
                                ->get();

                            $this->ticket->customFormFooter?->update([
                                'approved_by' => auth()->user()->id,
                            ]);

                            // Notify the agents through app and email.
                            $agents->each(function ($agent) {
                                Mail::to($agent)->send(new ApprovedTicketMail($this->ticket, $agent));
                                Notification::send(
                                    $agent,
                                    new AppNotification(
                                        ticket: $this->ticket,
                                        title: "Ticket #{$this->ticket->ticket_number} (Approved)",
                                        message: "You have a new ticket. "
                                    )
                                );
                            });

                            $this->actionOnSubmit();
                            ActivityLog::make(ticket_id: $this->ticket->id, description: 'approved the ticket');
                        }
                    } else {
                        noty()->addInfo('Ticket has already been approved by other Service Department Admin.');
                    }
                });
            } else {
                noty()->addError('You have no rights/permission to approve the ticket.');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.approver.ticket.approve-ticket');
    }
}
