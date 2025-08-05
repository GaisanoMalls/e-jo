<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ReopenTicket extends Component
{
    public Ticket $ticket;

    public function reopenTicket()
    {
        try {
            if (auth()->user()->isAgent() || auth()->user()->isServiceDepartmentAdmin()) {
                if ($this->ticket->status_id === Status::CLOSED) {
                    $this->ticket->update(['status_id' => Status::CLAIMED]);

                    // Get the requester's service department admins.
                    $serviceDeptAdmins = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
                        ->whereHas('buDepartments', fn($query) => $query->whereIn('departments.id', $this->ticket->user->buDepartments->pluck('id')))
                        ->whereHas('branches', fn($query) => $query->whereIn('branches.id', $this->ticket->user->branches->pluck('id')))
                        ->get();

                    // Get the current service department admin
                    $currentServiceDeptAdmin = User::role(Role::SERVICE_DEPARTMENT_ADMIN)->with('profile')->find(auth()->user()->id);

                    // Notify the requester's service department admins
                    $serviceDeptAdmins->each(function ($serviceDeptAdmin) use ($currentServiceDeptAdmin) {
                        Notification::send(
                            $serviceDeptAdmin,
                            new AppNotification(
                                ticket: $this->ticket,
                                title: "Ticket #{$this->ticket->ticket_number} (Reopened)",
                                message: $currentServiceDeptAdmin?->profile->getFullName . " reopened the ticket."
                            )
                        );
                    });

                    // Get the current agent
                    $currentAgent = User::role(Role::AGENT)->with('profile')->find(auth()->user()->id);

                    // Notify the requester
                    Notification::send(
                        $this->ticket->user,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Ticket #{$this->ticket->ticket_number} (Reopened)",
                            message: $currentAgent?->profile->getFullName . " reopened the ticket."
                        )
                    );
                    ActivityLog::make(ticket_id: $this->ticket->id, description: 'reopened the ticket');
                } else if ($this->ticket->status_id === Status::OPEN) {
                    noty()->addInfo('Ticket has already been reopened.');
                }
            } else {
                noty()->addWarning('The ticket has already been reopened.');
            }
            return redirect()->route('staff.ticket.view_ticket', $this->ticket->id);
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.reopen-ticket');
    }
}
