@extends('layouts.user.base', ['title' => 'On Process Tickets'])

@section('ticket-list-header')
<h5 class="mb-0 content__title">On Process Tickets</h5>
@endsection

@section('count-items')
<small class="count-item">{{ $onProcessTickets->count() }} items</small>
@endsection

@section('main-content')
<div class="row mx-0">
    @if (!$onProcessTickets->isEmpty())
    <div class="card ticket__card" id="userTicketCard">
        <div class="table-responsive">
            <table class="table mb-0 custom__table" id="userTable">
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
                    @foreach ($onProcessTickets as $ticket)
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="py-3 px-4 rounded-3" style="margin: 20px 0px; background-color: #e9ecef;">
        <small style="font-size: 14px;">No on process tickets.</small>
    </div>
    @endif
</div>
@endsection