<div>
    <div class="btn-group">
        <button type="button" class="btn btn-sm d-flex align-items-center gap-2 rounded-2 sort__button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-filter"></i>
            {{ $allApprovedTickets ? 'All' : ($withCosting ? 'With Costing' : ($withOutCosting ? 'Without Costing' : '')) }}
            <small class="text-muted" style="font-size: 12px;">
                ({{ $approvedTickets->count() }})
            </small>
        </button>
        <ul class="dropdown-menu dropdown-menu-start slideIn animate sort__button__dropdown">
            <li>
                <button wire:click="filterAll" class="dropdown-item d-flex align-item gap-2" type="button">
                    All
                </button>
            </li>
            <li>
                <button wire:click="filterApprovedTicketsWithCosting" class="dropdown-item d-flex align-item gap-2"
                    type="button">
                    With Costing
                </button>
            </li>
            <li>
                <button wire:click="filterApprovedTicketsWithoutCosting"
                    class="dropdown-item d-flex align-items-center gap-2" type="button">
                    Without Costing
                </button>
            </li>
        </ul>
    </div>
    <div class="row mx-0">
        @if ($approvedTickets->isNotEmpty())
            <div class="card ticket__card" id="userTicketCard">
                <div class="table-responsive">
                    <table class="table mb-0 custom__table">
                        <thead>
                            <tr>
                                <th class="table__head__label">Date Created</th>
                                <th class="table__head__label">Ticket No.</th>
                                <th class="table__head__label">Created By</th>
                                <th class="table__head__label">Subject</th>
                                <th class="table__head__label">Assigned To</th>
                                <th class="table__head__label">Priority Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($approvedTickets as $ticket)
                                <tr onclick="window.location='{{ route('user.ticket.view_ticket', $ticket->id) }}'">
                                    <td class="custom__table__data">
                                        <div class="ticket__list__status__line"
                                            style="background-color: {{ $ticket->priorityLevel->color ?? '' }};"></div>
                                        <p class="mb-0">{{ $ticket->dateCreated() }}</p>
                                    </td>
                                    <td class="custom__table__data d-flex gap-3">
                                        <p class="mb-0">{{ $ticket->ticket_number }}</p>
                                        <div class="d-flex align-items-center gap-2 text-muted">
                                            <i class="fa-regular fa-comment-dots"></i>
                                            <small>{{ $ticket->replies->count() }}</small>
                                        </div>
                                    </td>
                                    <td class="custom__table__data">
                                        <p class="mb-0">{{ $ticket->user->getBUDepartments() }}</p>
                                    </td>
                                    <td class="custom__table__data">
                                        <p class="mb-0">{{ Str::limit($ticket->subject, 30) }}</p>
                                    </td>
                                    <td class="custom__table__data">
                                        @if ($ticket->agent)
                                            <p class="mb-0">{{ $ticket->agent->profile->getFullName() }}</p>
                                        @else
                                            <p class="mb-0">----</p>
                                        @endif
                                    </td>
                                    <td class="custom__table__data">
                                        <p class="mb-0" style="color: {{ $ticket->priorityLevel->color }};">
                                            {{ $ticket->priorityLevel->name ?? '' }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="py-3 px-4 rounded-3" style="margin: 20px 0px; background-color: #e9ecef;">
                <small style="font-size: 14px;">No approved tickets.</small>
            </div>
        @endif
    </div>
</div>
