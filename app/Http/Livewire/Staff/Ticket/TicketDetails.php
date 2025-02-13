<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\TicketSlaExtensionStatusEnum;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketSlaExtension;
use App\Models\User;
use Livewire\Component;

class TicketDetails extends Component
{
    use Utils;

    public Ticket $ticket;
    public ?User $slaExtensionRequestedBy;
    public bool $canExtendSLA = false;
    public bool $canApproveSlaExtension = false;
    public bool $isRequestingForSlaExtension = false;

    protected $listeners = ['loadTicketDetails' => '$refresh'];

    private function triggerEvents()
    {
        $events = [
            'loadTicketLogs',
            'loadTicketDetails',
            'loadBackButtonHeader',
            'loadTicketStatusTextHeader',
            'loadSidebarCollapseTicketStatus',
            'loadTicketLogs',
            'loadTicketDetails',
            'loadBackButtonHeader',
            'loadTicketStatusTextHeader',
            'loadSidebarCollapseTicketStatus',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    private function actionOnSubmit()
    {
        $this->triggerEvents();
    }

    public function removeAssignedTeam()
    {
        if (auth()->user()->isServiceDepartmentAdmin()) {
            $this->ticket->update(['team_id' => null]);
            $this->removeAssignedAgent();
            $this->actionOnSubmit();
            ActivityLog::make(ticket_id: $this->ticket->id, description: 'removed the team assigned on this ticket');
        } else {
            noty()->addWarning('You are not allowed to remove the assigned team');
        }
    }

    public function removeAssignedAgent()
    {
        if (auth()->user()->isServiceDepartmentAdmin()) {
            $this->ticket->update([
                'agent_id' => null,
                'status_id' => Status::APPROVED,
            ]);

            $this->actionOnSubmit();
            ActivityLog::make(ticket_id: $this->ticket->id, description: 'removed the agent assigned on this ticket');
        } else {
            noty()->addWarning('You are not allowed to remove the assigned agent');
        }
    }

    public function extendSLA()
    {
        sleep(1);
        TicketSlaExtension::updateOrCreate(
            ['ticket_id' => $this->ticket->id],
            [
                'requested_by' => auth()->user()->id,
                'status' => TicketSlaExtensionStatusEnum::REQUESTING->value
            ]
        );
        $this->emitSelf('loadTicketDetails');
    }

    public function deleteSlaExtension()
    {
        if (auth()->user()->isAgent()) {
            $this->ticket?->slaExtension()->delete();
            $this->emitSelf('loadTicketDetails');
        }
    }

    public function render()
    {
        if ($this->isSlaOverdue($this->ticket)) {
            $this->ticket->update(['status_id', Status::OVERDUE]);
        }

        $this->slaExtensionRequestedBy = $this->ticket?->slaExtension?->requestedBy;
        $this->isRequestingForSlaExtension = $this->ticket?->slaExtension?->status->value === TicketSlaExtensionStatusEnum::REQUESTING->value;
        $this->canExtendSLA = auth()->user()->isAgent() && $this->ticket->agent_id !== null;
        $this->canApproveSlaExtension = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
            ->whereHas('buDepartments', fn($buDepartment) => $buDepartment->whereIn('departments.id', $this->slaExtensionRequestedBy?->buDepartments->pluck('id') ?? []))->exists();

        return view('livewire.staff.ticket.ticket-details');
    }
}
