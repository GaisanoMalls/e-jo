<div class="card border-0 p-0 card__ticket__details card__ticket__details__right">
    <div class="ticket__details__card__body__right log__container">
        <div class="mb-3 d-flex justify-content-between">
            <small class="ticket__actions__label">Ticket Activity Logs</small>
        </div>
        <div class="d-flex flex-column">
            @foreach ( $ticket->activityLogs as $log)
            <div class="d-flex justify-content-between py-3 log__list
                                        {{ $ticket->activityLogs->count() > 1 ? 'border-bottom' : '' }}">
                <div class="d-flex gap-3">
                    <i class="bi bi-clock-history log__icon"></i>
                    <div class="d-flex align-items-start flex-column">
                        <h6 class="mb-1 log__description">
                            <strong class="causer__details">
                                {{ $log->causerDetails() }}
                            </strong>
                            {{ $log->description }}
                        </h6>
                        <small class="log__date">
                            {{ $log->dateCreated() }}
                        </small>
                    </div>
                </div>
                <small class="log__time">
                    {{ $log->created_at->diffForHumans(null, true) }}
                </small>
            </div>
            @endforeach
        </div>
    </div>
</div>