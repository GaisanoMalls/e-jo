<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\SubtaskStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\TicketSubtask as TSubtask;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class TicketSubtask extends Component
{
    public Ticket $ticket;
    public Collection $subtasks;
    public Collection $subtaskTeams;
    public Collection $subtaskAgents;
    public ?string $subtaskName = null;
    public ?int $subtaskTeam = null;
    public ?int $subtaskAgent = null;
    public array $subtaskStatuses = [];

    public ?int $editSubtaskId = null;
    public ?string $editSubtaskName = null;
    public ?int $editSubtaskTeam = null;    
    public ?int $editSubtaskAgent = null;

    public function rules()
    {
        $createSubtaskValidation = [
            'subtaskName' => 'required',
            'subtaskTeam' => 'required',
        ];

        $editSubtaskValidation = [
            'editSubtaskName' => 'required',
            'editSubtaskTeam' => 'required',
        ];

        return $this->editSubtaskId ? $editSubtaskValidation : $createSubtaskValidation;
    }

    public function messages()
    {
        return [
            'subtaskName.required' => 'Task name is required.',
            'subtaskTeam.required' => 'Team is required.',
            'editSubtaskName.required' => 'Task name is required.',
            'editSubtaskTeam.required' => 'Team is required.'
        ];
    }

    public function addSubtaskButton()
    {
        $this->resetValidation();
    }

    public function saveSubtask()
    {
        $this->validate();

        try {
            if (auth()->user()->isServiceDepartmentAdmin()) {
                $isTicketSubtaskExists = TSubtask::where([
                    ['ticket_id', $this->ticket->id],
                    ['name', $this->subtaskName]
                ])->exists();

                if ($isTicketSubtaskExists) {
                    $this->addError('subtaskName', 'Subtask is already exists');
                    return;
                }

                TSubtask::create([
                    'ticket_id' => $this->ticket->id,
                    'name' => $this->subtaskName,
                    'team_id' => $this->subtaskTeam,
                    'agent_id' => $this->subtaskAgent
                ]);

                $serviceDepartmentAdmin = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
                    ->with('profile')
                    ->find(auth()->user()->id);

                if ($this->subtaskAgent) {
                    $agent = User::role(Role::AGENT)->find($this->subtaskAgent);
                    Notification::send(
                        $agent,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Ticket #{$this->ticket->ticket_number} (Subtask Created)",
                            message: "{$serviceDepartmentAdmin?->profile->getFullName} a subtask named {$this->subtaskName} was assigned to you.",
                            forSubtask: true
                        )
                    );
                } else if ($this->subtaskTeam && !$this->subtaskAgent) {
                    $agents = User::role(Role::AGENT)->whereHas('teams', fn($team) => $team->where('teams.id', $this->subtaskTeam))->get();
                    $agents->each(function ($agent) use ($serviceDepartmentAdmin) {
                        Notification::send(
                            $agent,
                            new AppNotification(
                                ticket: $this->ticket,
                                title: "Ticket #{$this->ticket->ticket_number} (Subtask Created)",
                                message: "{$serviceDepartmentAdmin?->profile->getFullName} created a subtask named {$this->subtaskName} for your team.",
                                forSubtask: true
                            )
                        );
                    });
                }

                ActivityLog::make(ticket_id: $this->ticket->id, description: "created a subtask named {$this->subtaskName}");
                $this->resetExcept('ticket', 'subtasks', 'subtaskTeams', 'subtaskAgents');
                $this->dispatchBrowserEvent('close-create-subtask-modal');
                $this->emit('loadTicketLogs');
            } else {
                noty()->addWarning("You don't have permission to create a subtask.");
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function changeSubtaskStatus(TSubtask $subtask, string $subtaskStatus)
    {
        if (auth()->user()->isAgent() || auth()->user()->isServiceDepartmentAdmin()) {
            $this->ticket->subtasks()->where('id', $subtask->id)->update([
                'status' => $subtask->status === SubtaskStatusEnum::OPEN ? SubtaskStatusEnum::DONE : SubtaskStatusEnum::OPEN
            ]);

            ActivityLog::make(ticket_id: $this->ticket->id, description: "changed the status of the subtask {$subtask->name} from {$subtask->status->value} to {$subtaskStatus}");
            $this->emit('loadTicketLogs');
        } else {
            noty()->addWarning("You don't have permission to change the subtask status.");
        }
    }

    public function editSubtask(TSubtask $subtask)
    {
        $this->resetValidation();

        $this->editSubtaskId = $subtask->id;
        $this->editSubtaskName = $subtask->name;
        $this->editSubtaskTeam = $subtask->team_id;
        $this->editSubtaskAgent = $subtask->agent_id;

        $this->dispatchBrowserEvent('edit-subtask', [
            'editSubtaskTeam' => $this->editSubtaskTeam,
            'editSubtaskAgent' => $this->editSubtaskAgent
        ]);
    }

    public function updateSubtask()
    {
        $this->validate();

        try {
            if (auth()->user()->isServiceDepartmentAdmin()) {
                $isTicketSubtaskExists = TSubtask::where([
                    ['ticket_id', $this->ticket->id],
                    ['name', $this->editSubtaskName]
                ])->whereNot('id', $this->editSubtaskId)
                    ->exists();

                if ($isTicketSubtaskExists) {
                    $this->addError('editSubtaskName', 'Subtask is already exists');
                    return;
                }

                TSubtask::where([
                    ['id', $this->editSubtaskId],
                    ['ticket_id', $this->ticket->id]
                ])
                    ->update([
                        'name' => $this->editSubtaskName,
                        'team_id' => $this->editSubtaskTeam,
                        'agent_id' => $this->editSubtaskAgent
                    ]);

                $this->resetExcept('ticket', 'subtasks', 'subtaskTeams', 'subtaskAgents');
                $this->dispatchBrowserEvent('close-edit-subtask-modal');
            } else {
                noty()->addWarning("You don't have permission to update a subtask.");
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        $this->subtaskStatuses = SubtaskStatusEnum::toArray();
        $this->subtasks = $this->ticket->subtasks()->with('assignedAgent.profile')->orderByDesc('created_at')->get();
        $this->subtaskTeams = Team::whereHas('serviceDepartment', function ($query) {
            $query->where('service_departments.id', $this->ticket->serviceDepartment->id);
        })->whereHas('branches', function ($query) {
            $query->whereIn('branches.id', $this->ticket->user->branches->pluck('id'));
        })->get();
        $this->subtaskAgents = User::role(Role::AGENT)
            ->with('profile')
            ->whereHas('branches', function ($query) {
                $query->where('branches.id', $this->ticket->branch_id);
            })
            ->whereHas('serviceDepartments', function ($query) {
                $query->where('service_departments.id', $this->ticket->serviceDepartment->id);
            })->get();

        return view('livewire.staff.ticket.ticket-subtask');
    }
}
