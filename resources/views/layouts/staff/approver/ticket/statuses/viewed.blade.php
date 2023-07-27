@extends('layouts.staff.approver.base', ['title' => 'Open Tickets'])

@section('main-content')
<div class="row">
    <div class="mb-4 d-flex flex-wrap justify-content-between">
        <div class="d-flex align-items-center gap-4">
            <h5 class="page__header__title">Viewed</h5>
            <small class="fw-semibold mb-1" id="countSelectedChbx" style="color: #d32839;"></small>
        </div>
    </div>
</div>
<div class="row mx-0">
    @if ($viewedTickets->count() > 0 )
    <div class="card ticket__card" id="userTicketCard">
        <div class="table-responsive">
            <table class="table mb-0 custom__table" id="approverTable">
                <thead>
                    <tr>
                        <th class="table__head__label">Date Created</th>
                        <th class="table__head__label">Ticket No.</th>
                        <th class="table__head__label">Created By</th>
                        <th class="table__head__label">Subject</th>
                        <th class="table__head__label">Assigned To</th>
                        <th class="table__head__label">Priority Level</th>
                        <th class="table__head__label">Action/Approval Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewedTickets as $ticket)
                    <tr onclick="window.location='{{ route('approver.ticket.view_ticket_details', $ticket->id) }}'">
                        <td class="custom__table__data">
                            <div class="ticket__list__status__line"
                                style="background-color: {{ $ticket->priorityLevel->color ?? '' }};"></div>
                            <p class="mb-0">
                                {{ $ticket->dateCreated() }} @ {{ $ticket->created_at->format('g:i A') }}
                            </p>
                        </td>
                        <td class="custom__table__data clickable_td">
                            <p class="mb-0">{{ $ticket->ticket_number }}</p>
                        </td>
                        <td class="custom__table__data">
                            <p class="mb-0">{{ $ticket->user->department->name }}</p>
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
                        <td class="custom__table__data py-0">
                            <div class="d-flex align-items-center justify-content-start gap-2">
                                <form action="{{ route('approver.tickets.disapprove', $ticket->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn__disapprove__ticket">
                                        Disapprove
                                    </button>
                                </form>
                                <form action="{{ route('approver.tickets.approve', $ticket->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm shadow btn__approve__ticket">
                                        Approve
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="py-3 px-4 rounded-3" style="background-color: #e9ecef;">
        <small style="font-size: 14px;">No tickets.</small>
    </div>
    @endif
</div>
@endsection