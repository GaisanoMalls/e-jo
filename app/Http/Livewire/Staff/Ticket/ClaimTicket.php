<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ClaimTicket extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadClaimTicket' => '$refresh'];

    private function triggerEvents()
    {
        $events = [
            'loadTicketLogs',
            'loadClaimTicket',
            'loadTicketActions',
            'loadTicketDetails',
            'loadLevelOfApproval',
            'loadBackButtonHeader',
            'loadCostingButtonHeader',
            'loadTicketStatusTextHeader',
            'loadSidebarCollapseTicketStatus'
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    private function actionOnSubmit()
    {
        $this->triggerEvents();
    }

    public function claimTicket()
    {
        try {
            DB::transaction(function () {
                if (!is_null($this->ticket->agent_id)) {
                    noty()->addWarning('Ticket has already been claimed by another agent. Select another ticket to claim.');
                    return;
                }

                $this->ticket->update([
                    'agent_id' => auth()->user()->id,
                    'status_id' => Status::CLAIMED,
                ]);

                $this->ticket->teams()->attach($this->ticket->agent->teams->pluck('id')->toArray());

                $agent = User::find(auth()->user()->id)
                    ->with('profile')
                    ->withWhereHas('roles', fn($role) => $role->where('name', Role::AGENT))
                    ->withWhereHas('teams', function ($team) {
                        $team->whereIn('teams.id', $this->ticket->teams->pluck('id')->toArray());
                    })
                    ->withWhereHas('serviceDepartments', function ($serviceDepartment) {
                        $serviceDepartment->where('service_departments.id', $this->ticket->service_department_id);
                    })->first();

                $coAgents = User::whereNot('id', $agent->id)
                    ->withWhereHas('roles', fn($role) => $role->where('name', Role::AGENT))
                    ->withWhereHas('teams', function ($team) use ($agent) {
                        $team->whereIn('teams.id', $agent->teams->pluck('id')->toArray());
                    })
                    ->withWhereHas('serviceDepartments', function ($serviceDepartment) use ($agent) {
                        $serviceDepartment->where('service_departments.id', $agent->serviceDepartments->pluck('id')->first());
                    })->get();

                $coAgents->each(function ($coAgent) {
                    $coAgent->notifications->each(function ($notification) {
                        if ($notification->data['ticket']['id'] == $this->ticket->id) {
                            $notification->delete();
                        }
                    });
                });

                Notification::send(
                    $this->ticket->user,
                    new AppNotification(
                        ticket: $this->ticket,
                        title: "Ticket #{$this->ticket->ticket_number} (Claimed)",
                        message: "{$agent->profile->getFullName} has claimed your ticket."
                    )
                );

                ActivityLog::make(ticket_id: $this->ticket->id, description: 'claimed the ticket');

                $this->actionOnSubmit();
                noty()->addSuccess("You have claimed the ticket - {$this->ticket->ticket_number}.");
            });

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function isDoneFirstLevelApproval()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true],
        ])->exists();
    }

    public function render()
    {
        return view('livewire.staff.ticket.claim-ticket');
    }
}