@extends('layouts.staff.base', ['title' => 'Reopened Tickets'])

@section('page-header')
<div class="justify-content-between d-flex flex-wrap ticket__content__top">
    <div class="col-lg-7 col-md-5">
        <h3 class="page__header__title">Tickets</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Tickets</li>
            <li class="breadcrumb-item active">Reopened</li>
        </ol>
    </div>
</div>
@endsection

@section('main-content')
<div class="row">
    <div class="ticket__section">
        <div class="col-12">
            <div class="card d-flex flex-column tickets__card p-0">
                <div class="tickets__card__header pb-0 pt-4 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex flex-column me-3">
                            <h6 class="card__title">Reopened Tickets</h6>
                            <p class="card__description">
                                Respond the tickets sent by the requester
                            </p>
                        </div>
                        <div class="d-flex">
                            <button type="button"
                                class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                                data-bs-toggle="modal" data-bs-target="#filterUserWithRolesModal">
                                <i class="fa-solid fa-filter"></i>
                                <span class="button__name">Add filter</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="tickets__table__card">
                    <div class="table-responsive custom__table">
                        @if ($reopenedTickets->count() > 0)
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
                                @foreach ($reopenedTickets as $ticket)
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
                                                {{ $ticket->user->department->name }} -
                                                {{ $ticket->user->branch->name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-start td__content">
                                            <span>{{ $ticket->subject }}</span>
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
                            <small style="font-size: 14px;">No records for reopened tickets.</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection