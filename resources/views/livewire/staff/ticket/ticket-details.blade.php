<div>
    <div class="card border-0 p-0 card__ticket__details">
        <div class="ticket__details__card__body__right">
            <div class="d-flex align-items-center gap-2 mb-3">
                <small class="ticket__actions__label">Ticket Details</small>
                <div wire:loading class="spinner-border spinner-border-sm loading__spinner" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div wire:loading.class="text-muted" class="d-flex flex-column gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <small class="ticket__details__info__label">
                        Approval status:
                    </small>
                    <small class="ticket__details__info">
                        @if ($ticket->approval_status == App\Models\ApprovalStatus::APPROVED)
                            <i class="fa-solid fa-circle-check me-1" style="color: green; font-size: 11px;"></i>
                            Approved
                        @elseif ($ticket->approval_status == App\Models\ApprovalStatus::FOR_APPROVAL)
                            <i class="fa-solid fa-paper-plane me-1" style="color: orange; font-size: 11px;"></i>
                            For Approval
                        @elseif ($ticket->approval_status == App\Models\ApprovalStatus::DISAPPROVED)
                            <i class="fa-solid fa-xmark me-1" style="color: red; font-size: 11px;"></i>
                            Disapproved
                        @else
                            ----
                        @endif
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <small class="ticket__details__info__label">Branch:</small>
                    <small class="ticket__details__info">
                        <i class="fa-solid fa-location-dot me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->branch->name }}
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <small class="ticket__details__info__label">
                        Service department:</small>
                    <small class="ticket__details__info">
                        <i class="fa-solid fa-gears me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->serviceDepartment->name }}
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <small class="ticket__details__info__label">Team:</small>
                    <small class="position-relative ticket__details__info">
                        <i class="fa-solid fa-people-group me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->getTeams() }}
                        @if (
                            $ticket->team_id &&
                                auth()->user()->hasRole(App\Models\Role::SERVICE_DEPARTMENT_ADMIN))
                            <i wire:click="removeAssignedTeam" class="bi bi-x ms-2 text-danger position-absolute"
                                style="font-size: 17px; transform: translateY(-10%); margin-left: 1px !important;"></i>
                        @endif
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <small class="ticket__details__info__label">
                        Help topic:
                    </small>
                    <small class="ticket__details__info">
                        <i class="bi bi-question-circle-fill me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->helpTopic->name ?? '----' }}
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <small class="ticket__details__info__label">
                        Assigned agent:
                    </small>
                    <small
                        class="position-relative ticket__details__info {{ $ticket->agent_id != null ? '' : 'not__set' }}">
                        <i class="fa-solid fa-user-check me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->agent_id != null ? $ticket->agent->profile->getFullName() : '----' }}
                        @if (
                            $ticket->agent_id &&
                                auth()->user()->hasRole(App\Models\Role::SERVICE_DEPARTMENT_ADMIN))
                            <i wire:click="removeAssignedAgent" class="bi bi-x ms-2 text-danger position-absolute"
                                style="font-size: 17px; transform: translateY(-10%); margin-left: 1px !important;"></i>
                        @endif
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <small class="ticket__details__info__label">
                        SLA:</small>
                    <div class="d-flex align-items-center gap-2">
                        @livewire('staff.ticket.sla-timer', ['ticket' => $ticket])
                        <small class="ticket__details__info" id="slaDays">
                            <i class="fa-solid fa-clock me-1 text-muted" style="font-size: 11px;"></i>
                            {{ $ticket->sla->time_unit ?? '----' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#assignTicketModal').modal('hide');
        });
    </script>
@endpush
