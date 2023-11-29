<div wire:poll.visible.7s>
    <div class="tickets__table__card">
        <div class="table-responsive custom__table">
            @if (!$closedTickets->isEmpty())
            <table class="table table-striped mb-0" id="table">
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px">
                            Date Created
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
                    @foreach ($closedTickets as $ticket)
                    <tr class="ticket__tr"
                        onclick="window.location='{{ route('staff.ticket.view_ticket', $ticket->id) }}'">
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
                                <span style="color: {{ $ticket->priorityLevel->color }};">{{
                                    $ticket->priorityLevel->name }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                <small style="font-size: 14px;">No records for closed tickets.</small>
            </div>
            @endif
        </div>
    </div>
</div>