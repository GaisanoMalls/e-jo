<?php

namespace App\Http\Livewire\Staff\Ticket;

use App;
use App\Enums\TicketSlaExtensionStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\ServiceLevelAgreement;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketSlaExtension;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class TicketDetails extends Component
{
    use Utils, BasicModelQueries;

    public Ticket $ticket;
    public ?User $slaExtensionRequestedBy;
    public Collection $serviceLevelAgreements;
    public bool $canExtendSLA = false;
    public bool $isSlaExtensionApprover = false;
    public bool $isRequestingForSlaExtension = false;
    public bool $isSlaExtensionApproved = false;
    public bool $isNewSlaSet = false;
    public ?string $slaExtensionRequester = null;

    public ?int $selectedSla = null;
    public ?ServiceLevelAgreement $selectedServiceLevelAgreement = null;

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
        try {
            if (auth()->user()->isServiceDepartmentAdmin()) {
                $this->ticket->update(['team_id' => null]);
                $this->removeAssignedAgent();
                $this->actionOnSubmit();
                ActivityLog::make(ticket_id: $this->ticket->id, description: 'removed the team assigned on this ticket.');
            } else {
                noty()->addWarning('You are not allowed to remove the assigned team');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function removeAssignedAgent()
    {
        try {
            if (auth()->user()->isServiceDepartmentAdmin()) {
                $this->ticket->update([
                    'agent_id' => null,
                    'status_id' => Status::APPROVED,
                ]);

                $this->actionOnSubmit();
                ActivityLog::make(ticket_id: $this->ticket->id, description: 'removed the agent assigned on this ticket');
            } else {
                noty()->addWarning('You are not allowed to remove the assigned agent.');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function requestSlaExtension()
    {
        try {
            sleep(1);
            if (auth()->user()->isAgent() && !$this->ticket->has_reached_due_date) {
                TicketSlaExtension::updateOrCreate(
                    ['ticket_id' => $this->ticket->id],
                    [
                        'requested_by' => auth()->user()->id,
                        'status' => TicketSlaExtensionStatusEnum::REQUESTING->value,
                        'is_new_sla_set' => false
                    ]
                );
                ActivityLog::make(ticket_id: $this->ticket->id, description: 'sent a request for SLA extension');

                $slaRequester = User::role(Role::AGENT)
                    ->with('buDepartments')
                    ->where('id', auth()->user()->id)
                    ->first();

                $slaExtensionApprovers = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
                    ->whereHas('buDepartments', function ($buDepartment) use ($slaRequester) {
                        $buDepartment->whereIn('departments.id', $slaRequester->buDepartments->pluck('id'));
                    })
                    ->get();

                $slaExtensionApprovers->each(function ($slaExtensionApprover) {
                    Notification::send(
                        $slaExtensionApprover,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Ticket #{$this->ticket->ticket_number} (Request for SLA Extension)", //SLA extension request has been approved.",
                            message: "You have a new request for SLA extension."
                        )
                    );
                });

                $this->emit('loadTicketLogs');
            } else {
                noty()->addWarning('You are not allowed to request for SLA extension.');
            }

            $this->emitSelf('loadTicketDetails');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function cancelSlaExtensionRequest()
    {
        try {
            if (auth()->user()->isAgent() && !$this->ticket->has_reached_due_date) {
                $this->ticket?->slaExtension()?->delete();
                ActivityLog::make(ticket_id: $this->ticket->id, description: 'cancelled the request for SLA extension');
                return redirect()->route('staff.ticket.view_ticket', $this->ticket->id);
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function approveSlaExtension()
    {
        try {
            if (auth()->user()->isServiceDepartmentAdmin() && !$this->ticket->has_reached_due_date) {
                if ($this->ticket->slaExtension->status->value === TicketSlaExtensionStatusEnum::REQUESTING->value) {
                    $this->ticket->slaExtension()->update([
                        'status' => TicketSlaExtensionStatusEnum::APPROVED
                    ]);

                    noty()->addSuccess('SLA extension request has been successfully approved.');
                    ActivityLog::make(ticket_id: $this->ticket->id, description: 'approved the SLA extension request');
                    Notification::send(
                        $this->slaExtensionRequestedBy,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Ticket #{$this->ticket->ticket_number} (Approved SLA Extension)", //SLA extension request has been approved.",
                            message: "Your SLA extension request has been approved."
                        )
                    );
                    return redirect()->route('staff.ticket.view_ticket', $this->ticket->id);
                } else {
                    noty()->addInfo('SLA extension has already been approved.');
                }
            } else {
                noty()->addWarning('You are not allowed to approve the SLA extension.');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function rejectSlaExtension()
    {
        try {
            sleep(1);
            if (auth()->user()->isServiceDepartmentAdmin() && !$this->ticket->has_reached_due_date) {
                $this->ticket?->slaExtension()->delete();
                noty()->addSuccess('Request for SLA extension has been rejected.');

                ActivityLog::make(ticket_id: $this->ticket->id, description: 'rejected the SLA extension request');
                Notification::send(
                    $this->slaExtensionRequestedBy,
                    new AppNotification(
                        ticket: $this->ticket,
                        title: "Ticket #{$this->ticket->ticket_number} (Rejected SLA Extension)", //SLA extension request has been approved.",
                        message: "Your request for SLA extension has been rejected."
                    )
                );

                $this->emitSelf('loadTicketDetails');
                $this->emit('loadTicketLogs');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function saveNewSla()
    {
        try {
            if (auth()->user()->isServiceDepartmentAdmin() && !$this->ticket->has_reached_due_date) {
                if (!$this->selectedSla) {
                    $this->addError('selectedSla', 'SLA field is required.');
                    return;
                }

                $this->ticket->update([
                    'service_level_agreement_id' => $this->selectedSla
                ]);

                $this->ticket->slaExtension()->update([
                    'is_new_sla_set' => true
                ]);

                ActivityLog::make(ticket_id: $this->ticket->id, description: "changed the SLA from {$this->ticket->sla->time_unit} to {$this->selectedServiceLevelAgreement->time_unit}");
                Notification::send(
                    $this->slaExtensionRequestedBy,
                    new AppNotification(
                        ticket: $this->ticket,
                        title: "Ticket #{$this->ticket->ticket_number} (Updated SLA)", //SLA extension request has been approved.",
                        message: "Ticket's SLA has been changed from {$this->ticket->sla->time_unit} to {$this->selectedServiceLevelAgreement->time_unit}."
                    )
                );

                noty()->addSuccess('SLA has been successfully updated from ' . $this->ticket->sla->time_unit . ' to ' . $this->selectedServiceLevelAgreement->time_unit . ".");
                $this->reset('selectedSla');
                $this->resetValidation('selectedSla');
            }

            return redirect()->route('staff.ticket.view_ticket', $this->ticket->id);
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updatedSelectedSla($value)
    {
        $this->selectedServiceLevelAgreement = ServiceLevelAgreement::find($value);
    }

    public function render()
    {
        if ($this->isSlaOverdue($this->ticket)) {
            $this->ticket->update(['status_id', Status::OVERDUE]);
        }

        $this->slaExtensionRequestedBy = $this->ticket?->slaExtension?->requestedBy;
        $this->slaExtensionRequester = $this->ticket?->slaExtension?->requestedBy?->profile->getFullName;
        $this->canExtendSLA = auth()->user()->isAgent() && $this->ticket->agent_id !== null;
        $this->isRequestingForSlaExtension = $this->ticket?->slaExtension?->status->value === TicketSlaExtensionStatusEnum::REQUESTING->value;
        $this->isSlaExtensionApproved = $this->ticket?->slaExtension?->status->value === TicketSlaExtensionStatusEnum::APPROVED->value;
        $this->isNewSlaSet = $this->ticket?->slaExtension?->is_new_sla_set ?? false;
        $this->isSlaExtensionApprover = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
            ->where('id', auth()->user()->id)
            ->whereHas('buDepartments', fn($buDepartment) => $buDepartment->whereIn('departments.id', $this->slaExtensionRequestedBy?->buDepartments->pluck('id') ?? []))
            ->first() !== null;
        $this->serviceLevelAgreements = $this->queryServiceLevelAgreements();

        return view('livewire.staff.ticket.ticket-details');
    }
}
