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
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketSubtask extends Component
{
    public Ticket $ticket;
    public Collection $subtasks;
    public Collection $subtaskTeams;
    public Collection $subtaskAgents;
    public ?string $taskName = null;
    public ?int $taskTeam = null;
    public ?int $taskAgent = null;
    public array $subtaskStatuses = [];

    public function mount()
    {
        $this->subtaskStatuses = SubtaskStatusEnum::toArray();

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
    }

    public function rules()
    {
        return [
            'taskName' => 'required',
            'taskTeam' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'taskName.required' => 'Task name is required.',
            'taskTeam.required' => 'Team is required.'
        ];
    }

    public function saveSubtask()
    {
        $this->validate();

        try {
            TSubtask::create([
                'ticket_id' => $this->ticket->id,
                'name' => $this->taskName,
                'team_id' => $this->taskTeam,
                'agent_id' => $this->taskAgent
            ]);

            ActivityLog::make(ticket_id: $this->ticket->id, description: 'created a subtask named "' . $this->taskName . '"');
            $this->reset('taskName', 'taskTeam', 'taskAgent');
            $this->dispatchBrowserEvent('close-subtask-modal');
            $this->emit('loadTicketLogs');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function changeSubtaskStatus(TSubtask $subtask)
    {
        $this->ticket->subtasks()->where('id', $subtask->id)->update([
            'status' => $subtask->status === SubtaskStatusEnum::OPEN ? SubtaskStatusEnum::DONE : SubtaskStatusEnum::OPEN
        ]);
    }

    public function render()
    {
        $this->subtasks = $this->ticket->subtasks()->with('assignedAgent.profile')->orderByDesc('created_at')->get();
        return view('livewire.staff.ticket.ticket-subtask');
    }
}
