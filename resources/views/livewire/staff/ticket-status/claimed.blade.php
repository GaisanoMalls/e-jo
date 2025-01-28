<div>
    <div class="tickets__card__header px-4 py-5">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-5">
            <div class="d-flex flex-column me-3">
                <h6 class="card__title">Claimed Tickets</h6>
                <p class="card__description mb-0">
                    List of tickets that have been claimed by the agents
                </p>
            </div>
            <div class="d-flex align-items-center justify-content-lg-between flex-wrap gap-3">
                @if (!$this->isEmptyFilteredTickets())
                    @if ($useMonth || $useDateRange)
                        <button wire:click="toggleDate" type="button" class="btn btn-sm d-flex align-items-center justify-content-center rounded-2"
                            style="font-size: 12px; background-color: #f9fafb">
                            Date
                        </button>
                    @endif
                    <button wire:click="toggleMonth" type="button" class="btn btn-sm d-flex align-items-center justify-content-center rounded-2"
                        @style(['font-size: 12px;', 'background-color: #f9fafb', 'background-color: #beb34e' => $useMonth, 'color: #FFF' => $useMonth])>
                        Month
                    </button>
                    <button wire:click="toggleDateRange" type="button" class="btn btn-sm d-flex align-items-center justify-content-center rounded-2"
                        @style(['font-size: 12px;', 'background-color: #f9fafb', 'background-color: #beb34e' => $useDateRange, 'color: #FFF' => $useDateRange])>
                        Date Range
                    </button>
                @endif
                @if ($useDateRange)
                    <div class="d-flex align-items-center w-auto gap-3">
                        <div class="position-relative">
                            <label for="start-date" class="form-label position-absolute form__field__label" style="top: -25px;">From</label>
                            <input type="date" wire:model="searchStartDate" class="form-control form__field" id="start-date"
                                @disabled($this->isEmptyFilteredTickets())>
                        </div>
                        <div class="position-relative">
                            <label for="end-date" class="form-label position-absolute form__field__label" style="top: -25px;">To</label>
                            <input type="date" wire:model="searchEndDate" class="form-control form__field" id="end-date"
                                @disabled($this->isEmptyFilteredTickets())>
                        </div>
                    </div>
                @else
                    <div class="position-relative">
                        <label for="start-date" class="form-label position-absolute text-muted form__field__label" style="top: -25px;">
                            @if ($useMonth)
                                Month
                            @else
                                Date
                            @endif
                        </label>
                        @if ($useMonth)
                            <input type="month" wire:model="searchMonth" class="form-control form__field" id="start-date"
                                @disabled($this->isEmptyFilteredTickets())>
                        @else
                            @if (($useDate && !$useMonth) || !$useDateRange)
                                <input type="date" wire:model="searchDate" class="form-control form__field" id="start-date"
                                    @disabled($this->isEmptyFilteredTickets())>
                            @endif
                        @endif
                    </div>
                @endif
                <div class="d-flex align-items-center position-relative">
                    <input wire:model.debounce.400ms="searchTicket" type="search" id="search-ticket" class="form-control table__search__field"
                        placeholder="Search ticket" @disabled($this->isEmptyFilteredTickets())>
                    <label for="search-ticket" class="table__search__icon">
                        <i wire:loading.remove wire:target="searchTicket" class="fa-solid fa-magnifying-glass"></i>
                    </label>
                    <span wire:loading wire:target="searchTicket" class="spinner-border spinner-border-sm table__search__icon" role="status"
                        aria-hidden="true">
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm d-flex align-items-center rounded-2 sort__button gap-2" data-bs-toggle="dropdown"
                            aria-expanded="false" @disabled($this->isEmptyFilteredTickets())>
                            <div wire:loading wire:target="priorityLevelId" class="spinner-border spinner-border-sm loading__spinner" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <i wire:loading.remove wire:target="priorityLevelId" class="bi bi-filter"></i>
                            @php
                                $levelName = '';
                                if ($priorityLevelName === 'Low') {
                                    $levelName = $priorityLevelName;
                                } elseif ($priorityLevelName === 'Medium') {
                                    $levelName = $priorityLevelName;
                                } elseif ($priorityLevelName === 'High') {
                                    $levelName = $priorityLevelName;
                                } elseif ($priorityLevelName === 'Urgent') {
                                    $levelName = $priorityLevelName;
                                }
                            @endphp
                            <small style="font-size: 12px;">
                                {{ $levelName ?: 'Priority' }}
                            </small>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end slideIn animate sort__button__dropdown">
                            <li>
                                <button wire:click="filterAllPriorityLevels" class="dropdown-item d-flex align-item gap-2" type="button">
                                    All
                                </button>
                            </li>
                            @foreach ($priorityLevels as $priorityLevel)
                                <li>
                                    <button wire:click="filterPriorityLevel({{ $priorityLevel->id }})"
                                        class="dropdown-item d-flex align-items-center gap-2" type="button">
                                        <i class="bi bi-circle-fill" style="color: {{ $priorityLevel->color }} !important; font-size: 10px;"></i>
                                        {{ $priorityLevel->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm d-flex align-items-center rounded-2 sort__button gap-2" data-bs-toggle="dropdown"
                            aria-expanded="false" @disabled($this->isEmptyFilteredTickets())>
                            <div wire:loading wire:target="paginatePageNumber" class="spinner-border spinner-border-sm loading__spinner"
                                role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <i wire:loading.remove wire:target="paginatePageNumber" class="bi bi-list-ol"></i>
                            {{ $paginatePageNumber }} items
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end slideIn animate sort__button__dropdown" style="width: 20px !important;">
                            @foreach ($pageNumberOptions as $pageNumber)
                                <li>
                                    <button wire:click="selectPaginateNumber({{ $pageNumber }})"
                                        class="dropdown-item d-flex align-items-center gap-2" type="button">
                                        {{ $pageNumber }} items
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @if ($this->hasSearchQuery())
            <div class="mt-4 d-flex align-items-center gap-2">
                <small class="rounded-4 text-white" style="background-color: #FF0000; padding: 3px 10px; font-size: 12px;">
                    {{ $claimedTickets->count() > 1 ? $claimedTickets->count() . ' results found' : $claimedTickets->count() . ' result found' }}
                </small>
                <button wire:click="clearFilters" class="btn btn-sm d-flex align-items-center border-0 justify-content-center"
                    style="height: 20px; width: 20px;" data-tooltip="Clear filters" data-tooltip-position="top" data-tooltip-font-size="11px">
                    <i class="bi bi-x-lg fw-bold"></i>
                </button>
            </div>
        @endif
    </div>
    <div class="tickets__table__card">
        <div class="table-responsive custom__table">
            @if ($claimedTickets->isNotEmpty())
                <table class="mb-0 table">
                    <thead>
                        <tr>
                            <th class="table__head__label border-0" style="padding: 17px 30px">
                                Date Created
                            </th>
                            <th class="table__head__label border-0" style="padding: 17px 10px;">
                                Special Project
                            </th>
                            <th class="table__head__label border-0" style="padding: 17px 30px;">
                                Ticket Number
                            </th>
                            <th class="table__head__label border-0" style="padding: 17px 30px;">
                                Created By
                            </th>
                            <th class="table__head__label border-0" style="padding: 17px 30px;">
                                Subject
                            </th>
                            <th class="table__head__label border-0" style="padding: 17px 30px;">
                                Assigned To
                            </th>
                            <th class="table__head__label border-0" style="padding: 17px 30px;">
                                Priority
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($claimedTickets as $ticket)
                            <tr class="ticket__tr" onclick="window.location='{{ route('staff.ticket.view_ticket', $ticket->id) }}'">
                                <td class="position-relative">
                                    <div class="ticket__list__status__line" style="background-color: {{ $ticket->priorityLevel->color }};">
                                    </div>
                                    <div class="d-flex align-items-center td__content text-start">
                                        <span>
                                            {{ $ticket->dateCreated() }} @
                                            {{ $ticket->created_at->format('g:i A') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center td__content gap-3 p-0 text-start">
                                        <span>
                                            {!! $ticket->isSpecialProject()
                                                ? '<i class="bi bi-check-circle-fill "style="color: #FF0000;"></i>'
                                                : '<i class="bi bi-x-circle-fill" style="color: #c2c2cf;"></i>' !!}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center td__content gap-3 text-start">
                                        <span>{{ $ticket->ticket_number }}</span>
                                        <div class="d-flex align-items-center text-muted gap-2">
                                            <i class="fa-regular fa-comment-dots"></i>
                                            <small>{{ $ticket->replies->count() }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center td__content text-start">
                                        <span>
                                            {{ $ticket->user->getBUDepartments() }} -
                                            {{ $ticket->user->getBranches() }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center td__content text-start">
                                        <span>{{ Str::limit($ticket->subject, 30) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center td__content text-start">
                                        <span>
                                            @if ($ticket->agent)
                                                {{ $ticket->agent->profile->getFullName }}
                                            @else
                                                ----
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center td__content text-start">
                                        <span style="color: {{ $ticket->priorityLevel->color }};">{{ $ticket->priorityLevel->name }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="bg-light rounded-3 px-4 py-3" style="margin: 20px 29px;">
                    <small style="font-size: 14px;">No records for claimed tickets.</small>
                </div>
            @endif
        </div>
    </div>
</div>
