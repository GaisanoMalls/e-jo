<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Mail\Staff\AssignedAgentMail;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Status;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class AssignTicket extends Component
{
    use BasicModelQueries;

    public Ticket $ticket;
    public ?Collection $agents = null;
    public ?Collection $serviceDepartments = null;
    public ?ServiceDepartment $currentlyAssignedServiceDepartment = null;
    public ?int $selectedServiceDepartment = null;
    public $teams = [];
    public $selectedTeams = [];
    public $currentlyAssignedBuDepartment = null;
    public $currentlyAssignedTeams = [];
    public $currentlyAssignedAgent;
    public $agent;
    public $isSpecialProject;
    public $isMultipleTeams = false;

    protected $rules = [
        'agents' => '',
    ];

    public function mount()
    {
        $this->isSpecialProject = $this->ticket->isSpecialProject() !== null;
        $this->currentlyAssignedAgent = $this->ticket->agent_id;
        $this->currentlyAssignedTeams = $this->ticket->teams->pluck('id')->toArray();
        $this->currentlyAssignedServiceDepartment = $this->ticket->serviceDepartment;
        $this->serviceDepartments = ServiceDepartment::get();
        $this->teams = $this->currentlyAssignedServiceDepartment
            ->teams()
            ->withWhereHas('branches', fn($branch) => $branch->where('branches.id', $this->ticket->branch->id))
            ->get();
    }

    private function triggerEvents()
    {
        $events = [
            'loadTicketLogs',
            'loadTicketDetails',
            'loadTicketActions',
            'loadBackButtonHeader',
            'loadTicketStatusTextHeader'
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    private function actionOnSubmit()
    {
        $this->triggerEvents();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function updatedSelectedTeams()
    {
        $this->agents = User::with('profile')->role(Role::AGENT)
            ->withWhereHas('serviceDepartments', fn($query) => $query->where('service_departments.id', $this->ticket->serviceDepartment->id))
            ->withWhereHas('branches', fn($branch) => $branch->where('branches.id', $this->ticket->branch->id))
            ->withWhereHas('teams', fn($team) => $team->whereIn('teams.id', $this->selectedTeams))
            ->get();

        $this->dispatchBrowserEvent('get-agents-from-team', ['agents' => $this->agents]);
    }

    public function updatedSelectedServiceDepartment($value)
    {
        $this->teams = Team::where('service_department_id', $value)
            ->withWhereHas('branches', fn($branch) => $branch->where('branches.id', $this->ticket->branch->id))
            ->get();
        $this->dispatchBrowserEvent('selected-service-department', ['teams' => $this->teams]);
    }

    // public function updatedIsMultipleTeams()
    // {

    // }

    public function saveAssignTicket()
    {
        try {
            DB::transaction(function () {
                $this->ticket->update([
                    'service_department_id' => $this->selectedServiceDepartment,
                    'agent_id' => $this->agent ?: null,
                    'status_id' => Status::APPROVED
                ]);
                $this->ticket->teams()->sync($this->selectedTeams ?: null);

                $this->ticket->refresh();
                if ($this->ticket->agent_id !== null) {
                    $this->ticket->update([
                        'status_id' => Status::CLAIMED,
                        'approval_status' => ApprovalStatusEnum::APPROVED,
                    ]);

                    // Retrieve the service department administrator responsible for approving the ticket. For notification use only
                    $serviceDepartmentAdmin = User::with('profile')->where('id', auth()->user()->id)->role(Role::SERVICE_DEPARTMENT_ADMIN)->first();

                    Notification::send(
                        $this->ticket->agent,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Ticket #{$this->ticket->ticket_number} (Assigned)",
                            message: "{$serviceDepartmentAdmin->profile->getFullName} assign this ticket to you."
                        )
                    );
                    Mail::to($this->ticket->agent)->send(new AssignedAgentMail($this->ticket, $this->ticket->agent));
                    ActivityLog::make(ticket_id: $this->ticket->id, description: 'assigned the ticket');
                }
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.assign-ticket');
    }
}
