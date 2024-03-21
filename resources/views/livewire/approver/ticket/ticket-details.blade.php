<div>
    <div class="card border-0 p-0 card__ticket__details">
        <div class="ticket__details__card__body__right">
            <div class="d-flex align-items-center gap-2 mb-3">
                <small class="ticket__actions__label">Ticket Details</small>
                <div wire:loading class="spinner-border spinner-border-sm loading__spinner" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label" style="font-weight: 500;">
                        Approval status:
                    </small>
                    <small class="ticket__details__info">
                        @if ($ticket->approval_status === App\Enums\ApprovalStatusEnum::APPROVED)
                            <i class="fa-solid fa-circle-check me-1" style="color: green; font-size: 11px;"></i>
                            Approved
                        @elseif ($ticket->approval_status === App\Enums\ApprovalStatusEnum::FOR_APPROVAL)
                            <i class="fa-solid fa-paper-plane me-1" style="color: orange; font-size: 11px;"></i>
                            For Approval
                        @elseif ($ticket->approval_status === App\Enums\ApprovalStatusEnum::DISAPPROVED)
                            <i class="fa-solid fa-xmark me-1" style="color: red; font-size: 11px;"></i>
                            Disapproved
                        @else
                        @endif
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label" style="font-weight: 500;">Branch:</small>
                    <small class="ticket__details__info">
                        <i class="fa-solid fa-location-dot me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->branch->name }}
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label" style="font-weight: 500;">
                        Service department:</small>
                    <small class="ticket__details__info">
                        <i class="fa-solid fa-gears me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->serviceDepartment->name }}
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label" style="font-weight: 500;">Team:</small>
                    <small class="ticket__details__info">
                        <i class="fa-solid fa-people-group me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->team->name ?? '' }}
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label" style="font-weight: 500;">
                        Help topic:
                    </small>
                    <small class="ticket__details__info">
                        <i class="bi bi-question-circle-fill me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->helpTopic->name ?? '' }}
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label" style="font-weight: 500;">
                        Assigned agent:
                    </small>
                    <small class="ticket__details__info {{ $ticket->agent_id !== null ? '' : 'not__set' }}">
                        <i class="fa-solid fa-user-check me-1 text-muted" style="font-size: 11px;"></i>
                        @if ($ticket->agent)
                            {{ $ticket->agent->profile->getFullName() }}
                        @else
                            No agent
                        @endif
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label" style="font-weight: 500;">
                        SLA:
                    </small>
                    <div class="d-flex align-items-center gap-2">
                        @if ($isSlaApproved)
                            @livewire('sla-timer', ['ticket' => $ticket])
                        @endif
                        <small class="ticket__details__info">
                            <i class="fa-solid fa-clock me-1 text-muted {{ $isSlaApproved ? 'bx-flashing' : '' }}"
                                style="font-size: 11px;"></i>
                            {{ $ticket->sla->time_unit ?? '' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
