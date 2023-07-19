<div class="card border-0 p-0 card__ticket__details card__ticket__details__right">
    <div class="ticket__details__card__body__right">
        <div class="mb-3 d-flex justify-content-between">
            <small class="ticket__actions__label">Assigned Agent</small>
        </div>
        @if ($ticket->agent)
        <div class="d-flex gap-2">
            @if ($ticket->agent->profile->picture)
            <img src="{{ Storage::url($ticket->agent->profile->picture) }}" class="ticket__assigned__agent__picture"
                alt="">
            @else
            <div class="user__name__initial d-flex align-items-center p-2 justify-content-center rounded-circle text-white"
                style="background-color: #196837; height: 40px; width: 40px; font-size: 12px; border: 2px solid #d9ddd9;">
                {{ $ticket->agent->profile->getNameInitial() }}
            </div>
            @endif
            <div class="w-100 d-flex gap-2 flex-wrap align-items-center justify-content-between">
                <div class="d-flex flex-column me-2">
                    <small class="ticket__details__user__fullname">
                        <span>{{ $ticket->user->profile->getFullName() }}</span>
                    </small>
                    <small class="ticket__details__user__department">
                        {{ $ticket->agent->email }}
                    </small>
                </div>
                <button type="button" class="btn btn-sm btn__assigned__agent__details">See details</button>
            </div>
        </div>
        @else
        <div class="alert mb-0 border-0 py-2 px-3" style="font-size: 13px; background-color: #F5F7F9;" role="alert">
            There is no assigned agent for this ticket.
        </div>
        @endif
    </div>
</div>
