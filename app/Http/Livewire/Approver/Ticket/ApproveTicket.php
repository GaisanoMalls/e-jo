<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\TicketApprovalLevel;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ApproveTicket extends Component
{
    use TicketApprovalLevel;

    public Ticket $ticket;

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
     * Perform livewire events upon form submission.
     */
    private function actionOnSubmit()
    {
        $this->triggerEvents();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function isCurrentLevelApprover()
    {
        return $this->ticket->helpTopic->approvers()->where('user_id', auth()->user()->id)->exists();
    }

    public function approveTicket()
    {
        try {
            if (Auth::user()->hasRole(Role::APPROVER) && $this->isCurrentLevelApprover()) {
                DB::transaction(function () {
                    if ($this->ticket->status_id != Status::APPROVED && $this->ticket->approval_status != ApprovalStatusEnum::APPROVED) {
                        $approvedLevel = $this->approveLevelOfApproval($this->ticket);

                        if ($approvedLevel) {
                            $agents = User::with('profile')
                                ->withWhereHas('teams', function ($team) {
                                    $team->whereIn('teams.id', $this->ticket->teams->pluck('id')->toArray());
                                })
                                ->withWhereHas('serviceDepartments', function ($serviceDepartment) {
                                    $serviceDepartment->where('service_departments.id', $this->ticket->service_department_id);
                                })
                                ->role(Role::AGENT)
                                ->get();

                            $this->ticket->customFormFooter->update([
                                'approved_by' => auth()->user()->id,
                            ]);

                            // Notify the agents through app and email.
                            $agents->each(function ($agent) {
                                // Mail::to($agent)->send(new ApprovedTicketMail($this->ticket, $agent));
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
                        noty()->addInfo('Ticket has already been approved by other service dept. admin');
                    }
                });
            } else {
                noty()->addError('You have no rights/permission to approve the ticket');
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
