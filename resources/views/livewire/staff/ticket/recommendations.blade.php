<div>
    <div class="tickets__table__card">
        <div class="table-responsive custom__table">
            @if ($recommendations->isNotEmpty())
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Date Requested
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Ticket Number
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Requested By
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Approved
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recommendations as $recommendation)
                            <tr class="ticket__tr" onclick="window.location='{{ route('staff.ticket.view_ticket', $recommendation->ticket->id) }}'">
                                <td class="position-relative">
                                    <div class="ticket__list__status__line" style="background-color: {{ $recommendation->ticket->priorityLevel->color }};">
                                    </div>
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>
                                            {{ $recommendation->dateCreated() }} @
                                            {{ $recommendation->created_at->format('g:i A') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start gap-3 td__content p-0">
                                        <span>
                                            {{ $recommendation->ticket->ticket_number }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start gap-3 td__content p-0">
                                        <span>
                                            {{ $recommendation->requestedByServiceDeptAdmin->profile->getFullName }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start gap-3 td__content">
                                        {{ $recommendation->approval_status }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                    <small style="font-size: 14px;">No records for approved tickets.</small>
                </div>
            @endif
        </div>
    </div>
</div>
