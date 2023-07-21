<div class="card border-0 p-0 card__ticket__details">
    <div class="ticket__details__card__body__right">
        <div class="mb-3">
            <label class="ticket__actions__label">Ticket details</label>
        </div>
        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center justify-content-between">
                <small class="ticket__details__info__label" style="font-weight: 500;">
                    Approval status:
                </small>
                <small class="ticket__details__info">
                    @if ($ticket->approval_status === 'approved')
                    <i class="fa-solid fa-circle-check" style="color: #D32839;"></i>
                    Approved
                    @elseif ($ticket->approval_status === 'for_approval')
                    <i class="fa-solid fa-paper-plane" style="color: #D32839;"></i>
                    For Approval
                    @elseif ($ticket->approval_status === 'disapproved')
                    <i class="fa-solid fa-xmark" style="color: #D32839;"></i>
                    Disapproved
                    @else
                    ----
                    @endif
                </small>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <small class="ticket__details__info__label" style="font-weight: 500;">Branch:</small>
                <small class="ticket__details__info">{{ $ticket->branch->name }}</small>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <small class="ticket__details__info__label" style="font-weight: 500;">
                    Service department:</small>
                <small class="ticket__details__info">{{ $ticket->serviceDepartment->name }}</small>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <small class="ticket__details__info__label" style="font-weight: 500;">Team:</small>
                <small class="ticket__details__info">{{ $ticket->team->name }}</small>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <small class="ticket__details__info__label" style="font-weight: 500;">
                    Help topic:
                </small>
                <small class="ticket__details__info">{{ $ticket->helpTopic->name }}</small>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <small class="ticket__details__info__label" style="font-weight: 500;">
                    SLA:</small>
                <small class="ticket__details__info">{{ $ticket->sla->time_unit ?? '----' }}</small>
            </div>
        </div>
    </div>
</div>