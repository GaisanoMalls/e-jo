<div>
    <div class="card border-0 p-0 card__ticket__details card__ticket__details__right">
        <div class="ticket__details__card__body__right log__container">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2">
                    <small class="ticket__actions__label">Ticket Activity Logs</small>
                    <div wire:loading class="spinner-border spinner-border-sm loading__spinner" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                @if (!$ticketLogs->isEmpty() || ($isAll || $isMyLogsOnly))
                <div class="btn-group">
                    <button type="button"
                        class="btn btn-sm d-flex align-items-center gap-2 px-2 py-1 rounded-2 dropdown-toggle my__logs"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-filter"></i>
                        {{ $isAll ? 'All' : ($isMyLogsOnly ? 'My logs' : 'All') }}
                        <small class="text-muted" style="font-size: 12px;">
                            ({{ $ticketLogs->count() }})
                        </small>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end slideIn animate my__logs__dropdown">
                        <li>
                            <button class="dropdown-item d-flex align-item gap-2" type="button" wire:click="filterAll">
                                <i class="bi bi-check2-all"></i>
                                All
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item d-flex align-items-center gap-2" type="button"
                                wire:click="filterMyLogs">
                                <i class="bi bi-person-fill-check"></i>
                                My logs
                            </button>
                        </li>
                    </ul>
                </div>
                @endif
            </div>
            <div class="d-flex flex-column">
                @if (!$ticketLogs->isEmpty())
                @foreach ($ticketLogs as $log)
                <div wire:loading.class="text-muted"
                    class="d-flex justify-content-between py-3 log__list {{ $ticketLogs->count() > 1 ? 'border-bottom' : '' }}">
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
                        ago
                    </small>
                </div>
                @endforeach
                @else
                <div class="rounded-3 mt-1" style="font-size: 0.8rem; padding: 12px 18px; background-color: #F5F7F9;">
                    No ticket logs
                </div>
                @endif
            </div>
        </div>
    </div>
</div>