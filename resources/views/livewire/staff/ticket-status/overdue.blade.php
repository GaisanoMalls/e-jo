<div>
    <div class="tickets__card__header py-5 px-4">
        <div class="d-flex flex-wrap gap-5 align-items-center justify-content-between">
            <div class="d-flex flex-column me-3">
                <h6 class="card__title">Overdue Tickets</h6>
                <p class="card__description mb-0">
                    Respond the tickets sent by the requester
                </p>
            </div>
            <div class="d-flex flex-wrap gap-3 align-items-center justify-content-lg-between">
                <div class="d-flex flex-column flex-wrap gap-1 position-relative">
                    <div class="w-100 d-flex align-items-center position-relative">
                        <input wire:model.debounce.400ms="searchTicket" type="text" class="form-control table__search__field" placeholder="Search ticket" @disabled($this->isEmptyOverdueTickets())>
                        <i wire:loading.remove wire:target="searchTicket" class="fa-solid fa-magnifying-glass table__search__icon"></i>
                        <span wire:loading wire:target="searchTicket" class="spinner-border spinner-border-sm table__search__icon" role="status" aria-hidden="true">
                        </span>
                    </div>
                    @if (!empty($searchTicket))
                        <div class="w-100 d-flex align-items-center gap-2 mb-1 position-absolute" style="font-size: 0.9rem; bottom: -25px;">
                            <small class="text-muted ">
                                {{ $overdueTickets->count() }} {{ $overdueTickets->count() > 1 ? 'results' : 'result' }} found
                            </small>
                            <small wire:click="clearSearchTicket" class="fw-regular text-danger clear__search">Clear</small>
                        </div>
                    @endif
                </div>
                <div class="d-flex gap-3 align-items-center w-auto">
                    <div class="position-relative">
                        <label for="start-date" class="form-label position-absolute form__field__label" style="top: -25px;">From</label>
                        <input type="date" wire:model="startDate" class="form-control form__field" id="start-date" @disabled($this->isEmptyOverdueTickets())>
                    </div>
                    <div class="position-relative">
                        <label for="end-date" class="form-label position-absolute form__field__label" style="top: -25px;">To</label>
                        <input type="date" wire:model="endDate" class="form-control form__field" id="end-date" @disabled($this->isEmptyOverdueTickets())>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm d-flex align-items-center gap-2 rounded-2 sort__button" data-bs-toggle="dropdown" aria-expanded="false" @disabled($this->isEmptyOverdueTickets())>
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
                            <small class="text-muted" style="font-size: 12px;">
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
                                    <button wire:click="filterPriorityLevel({{ $priorityLevel->id }})" class="dropdown-item d-flex align-items-center gap-2" type="button">
                                        <i class="bi bi-circle-fill" style="color: {{ $priorityLevel->color }} !important; font-size: 10px;"></i>
                                        {{ $priorityLevel->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tickets__table__card mt-3">
        <div class="table-responsive custom__table">
            @if ($overdueTickets->isNotEmpty())
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 table__head__label" style="padding: 17px 30px">
                                Date Created
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 10px;">
                                Special Project
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Ticket Number
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Created By
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Subject
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Assigned To
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Priority
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($overdueTickets as $ticket)
                            <tr class="ticket__tr" onclick="window.location='{{ route('staff.ticket.view_ticket', $ticket->id) }}'">
                                <td class="position-relative">
                                    <div class="ticket__list__status__line" style="background-color: {{ $ticket->priorityLevel->color }};">
                                    </div>
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>
                                            {{ $ticket->dateCreated() }} @
                                            {{ $ticket->created_at->format('g:i A') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start gap-3 td__content p-0">
                                        <span>
                                            {!! $ticket->isSpecialProject() ? '<i class="bi bi-check-circle-fill "style="color: #FF0000;"></i>' : '<i class="bi bi-x-circle-fill" style="color: #c2c2cf;"></i>' !!}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start gap-3 td__content">
                                        <span>{{ $ticket->ticket_number }}</span>
                                        <div class="d-flex align-items-center gap-2 text-muted">
                                            <i class="fa-regular fa-comment-dots"></i>
                                            <small>{{ $ticket->replies->count() }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>
                                            {{ $ticket->user->getBUDepartments() }} -
                                            {{ $ticket->user->getBranches() }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ Str::limit($ticket->subject, 30) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start td__content">
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
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span style="color: {{ $ticket->priorityLevel->color }};">{{ $ticket->priorityLevel->name }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                    <small style="font-size: 14px;">No records for overdue tickets.</small>
                </div>
            @endif
        </div>
    </div>
</div>
