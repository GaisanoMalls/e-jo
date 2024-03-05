<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\BasicModelQueries;
use App\Models\Role;
use App\Models\Status;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\ServiceDepartmentAdmin\AssignedAgentNotification;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class AssignTicket extends Component
{
    use BasicModelQueries;

    public Ticket $ticket;
    public $agents = [];
    public $team;
    public $agent;
    public $isSpecialProject;
    public $isMultipleTeams = false;

    public function mount()
    {
        $this->isSpecialProject = !is_null($this->ticket->isSpecialProject());
    }

    private function actionOnSubmit()
    {
        $this->emit('loadTicketDetails');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function saveAssignTicket()
    {
        try {
            DB::transaction(function () {
                $this->ticket->update(['agent_id' => $this->agent ?: null]);
                $this->ticket->teams()->sync($this->team ?: null);

                $this->ticket->refresh();
                if (!is_null($this->ticket->agent_id)) {
                    $this->ticket->update([
                        'status_id' => Status::CLAIMED,
                        'approval_status' => ApprovalStatusEnum::APPROVED,
                    ]);

                    Notification::send($this->ticket->agent, new AssignedAgentNotification($this->ticket));
                    // Mail::to($this->ticket->agent)->send(new AssignedAgentMail($this->ticket, $this->ticket->agent));
                }

                $this->emit('loadBackButtonHeader');
                $this->emit('loadTicketStatusTextHeader');
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong');
        }
    }

    public function updatedTeam()
    {
        $this->agents = User::with('profile')->role(Role::AGENT)
            ->whereHas('serviceDepartments', fn(Builder $query) => $query->where('service_departments.id', $this->ticket->serviceDepartment->id))
            ->whereHas('branches', fn(Builder $branch) => $branch->where('branches.id', $this->ticket->branch->id))
            ->whereHas('teams', fn(Builder $team) => $team->where('teams.id', $this->team))->get();
        $this->dispatchBrowserEvent('get-agents-from-team', ['agents' => $this->agents->toArray()]);
    }

    public function render()
    {
        return view('livewire.staff.ticket.assign-ticket', [
            'agents' => $this->agents,
            'teams' => Team::where('service_department_id', $this->ticket->serviceDepartment->id)
                ->withWhereHas('branches', fn(Builder $branch) => $branch->where('branches.id', $this->ticket->branch->id))
                ->get(),
        ]);
    }
}
