<div>
    <div class="tickets__card__header pb-0 pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column me-3">
                <h6 class="card__title">Open Tickets</h6>
                <p class="card__description">
                    Respond the tickets sent by the requester
                </p>
            </div>
            <div class="d-flex">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm d-flex align-items-center gap-2 rounded-2 sort__button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-filter"></i>
                        {{ $allOpenTickets ? 'All' : ($withPr ? 'With PR' : ($withoutPr ? 'Without PR' : '')) }}
                        <small class="text-muted" style="font-size: 12px;">
                            ({{ $openTickets->count() }})
                        </small>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end slideIn animate sort__button__dropdown">
                        <li>
                            <button wire:click="filterAllOpenTickets" class="dropdown-item d-flex align-item gap-2"
                                type="button">
                                All
                            </button>
                        </li>
                        <li>
                            <button wire:click="filterOpenTicketsWithPr" class="dropdown-item d-flex align-item gap-2"
                                type="button">
                                With PR
                            </button>
                        </li>
                        <li>
                            <button wire:click="filterOpenTicketsWithoutPr"
                                class="dropdown-item d-flex align-items-center gap-2" type="button">
                                Without PR
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="tickets__table__card">
        <div class="table-responsive custom__table">
            @if ($openTickets->isNotEmpty())
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
                        @foreach ($openTickets as $ticket)
                            @if (auth()->user()->hasRole(App\Models\Role::SERVICE_DEPARTMENT_ADMIN))
                                <tr wire:key="seen-ticket-{{ $ticket->id }}"
                                    wire:click="seenTicket({{ $ticket->id }})" class="ticket__tr">
                                @else
                                <tr class="ticket__tr"
                                    onclick="window.location='{{ route('staff.ticket.view_ticket', $ticket->id) }}'">
                            @endif
                            <td class="position-relative">
                                <div class="ticket__list__status__line"
                                    style="background-color: {{ $ticket->priorityLevel->color }};">
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
                                        {!! $ticket->isSpecialProject()
                                            ? '<i class="bi bi-check-circle-fill "style="color: #FF0000;"></i>'
                                            : '<i class="bi bi-x-circle-fill" style="color: #c2c2cf;"></i>' !!}
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
                                            {{ $ticket->agent->profile->getFullName() }}
                                        @else
                                            ----
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span
                                        style="color: {{ $ticket->priorityLevel->color }};">{{ $ticket->priorityLevel->name }}</span>
                                </div>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class=" bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                    <small style="font-size: 14px;">No records for open tickets.</small>
                </div>
            @endif
        </div>
    </div>
</div>
