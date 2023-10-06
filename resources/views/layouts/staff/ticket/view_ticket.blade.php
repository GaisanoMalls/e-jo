@extends('layouts.staff.base', ['title' => $ticket->subject])

@section('main-content')
@if ($ticket)
<div class="ticket__section">
    <div class="row">
        <div class="col-xl-12 ticket__details__container">
            <div class="mb-3 ticket__details__top">
                @livewire('staff.ticket.load-back-button-header', ['ticket' => $ticket])
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        @livewire('staff.ticket.load-ticket-status-header-text', ['ticket' => $ticket])
                        <h6 class="ticket__details__ticketnumber mb-0">Ticket: {{ $ticket->ticket_number }}</h6>
                    </div>
                    @livewire('staff.ticket.priority-level', ['ticket' => $ticket])
                </div>
                <div class="d-flex flex-wrap gap-2 justify-content-between ticket__details__header">
                    <div class="mb-2">
                        <h6 class="ticket__details__title mb-0">{{ $ticket->subject }}</h6>
                        <small class="ticket__details__datetime">{{ $ticket->dateCreated() }},
                            {{ $ticket->created_at->format('D') }} @ {{ $ticket->created_at->format('g:i A') }}</small>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center gap-3 gap-lg-4 gap-xl-4">
                        @if (Route::is('staff.ticket.view_ticket'))
                        @livewire('staff.ticket.load-reply-button-header', ['ticket' => $ticket])
                        @endif
                        @if (Route::is('staff.ticket.ticket_clarifications') && auth()->user()->role_id ===
                        App\Models\Role::SERVICE_DEPARTMENT_ADMIN)
                        @livewire('staff.ticket.load-clarify-ticket-button-header', ['ticket' => $ticket])
                        @endif
                        @if (auth()->user()->role_id == App\Models\Role::AGENT && $ticket->status_id !=
                        App\Models\Status::CLOSED)
                        {{-- SHOW "Claim" BUTTON FOR AGENT USER ONLY --}}
                        <div class="d-flex flex-column">
                            @if ($ticket->status_id == App\Models\Status::CLAIMED)
                            <button style="background-color: {{ $ticket->status->color }} !important;"
                                class="btn btn-sm border-0 m-auto text-white ticket__detatails__btn__claim claimed d-flex  align-items-center justify-content-center">
                                <i class="fa-regular fa-flag"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label fw-bold">Claimed</small>
                            @else
                            @if ($ticket->agent_id == null)
                            <form action="{{ route('staff.ticket.ticket_details_claim_ticket', $ticket->id) }}"
                                method="post">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn bx-burst btn-sm border-0 m-auto ticket__detatails__btn__claim d-flex
                                    align-items-center justify-content-center">
                                    <i class="fa-regular fa-flag"></i>
                                </button>
                                <small class="ticket__details__topbuttons__label">Claim</small>
                            </form>
                            @else
                            <button style="background-color: #FF8B8B !important;"
                                class="btn btn-sm border-0 m-auto text-white ticket__detatails__btn__claim claimed d-flex  align-items-center justify-content-center">
                                <i class="fa-regular fa-flag"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label fw-bold">Claimed</small>
                            @endif
                            @endif
                        </div>
                        @elseif ($ticket->status_id == App\Models\Status::CLAIMED)
                        <div class="d-flex flex-column">
                            <button style="background-color: {{ $ticket->status->color }} !important;" class="btn btn-sm border-0 m-auto text-white ticket__detatails__btn__claim claimed d-flex
                                align-items-center justify-content-center">
                                <i class="fa-regular fa-flag"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label fw-bold">Claimed</small>
                        </div>
                        @endif
                        @livewire('staff.ticket.load-close-status-button-header', ['ticket' => $ticket])
                        @livewire('staff.ticket.dropdown-approval-button', ['ticket' => $ticket])
                        @livewire('staff.ticket.bookmark-ticket', ['ticket' => $ticket])
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 position-relative">
                    <div class="card border-0 p-0 card__ticket__details">
                        <div class="ticket__details__card__header d-flex flex-wrap justify-content-between">
                            <div class="d-flex align-items-center user__account__media">
                                @if ($ticket->user->profile->picture)
                                <img src="{{ Storage::url($ticket->user->profile->picture) }}"
                                    class="image-fluid ticket__details__user__picture" alt="">
                                @else
                                <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                    text-white" style="background-color: #24695C;">
                                    {{ $ticket->user->profile->getNameInitial() }}</div>
                                @endif
                                <div class="d-flex flex-column">
                                    <small class="ticket__details__user__fullname">
                                        <span>{{ $ticket->user->profile->getFullName() }}</span>
                                    </small>
                                    <small class="ticket__details__user__department">
                                        {{ $ticket->user->department->name }} - {{ $ticket->user->branch->name }}
                                    </small>
                                </div>
                            </div>
                            <small class="ticket__details__time mt-2">
                                {{ $ticket->created_at->diffForHumans(null, true) }} ago
                            </small>
                        </div>
                        <div class="ticket__details__card__body">
                            <div class="ticket__description">{!! $ticket->description !!}</div>
                            @if ($ticket->fileAttachments->count() > 0)
                            <div class="ticket__attachments d-inline-flex gap-1 mb-3" data-bs-toggle="modal"
                                data-bs-target="#ticketFilesModalForm">
                                <i class="fa-solid fa-file-zipper"></i>
                                <small class="attachment__count">{{ $ticket->fileAttachments->count() }}</small>
                                <small class="attachment__label">
                                    {{ $ticket->fileAttachments->count() > 1 ? 'file attachments' : 'file attachement'
                                    }}
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4 mt-4 d-flex align-items-center justify-content-between">
                        <small class="ticket__discussions text-muted">
                            @section('count-replyThreads-clarificafions')
                            @livewire('staff.ticket.load-ticket-discussion-count', ['ticket' => $ticket])
                            @show
                        </small>
                        <div class="d-flex align-items-center gap-3 threads__clarifications__tab__container">
                            @if (auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN)
                            <a onclick="window.location='{{ route('staff.ticket.view_ticket', $ticket->id) }}'"
                                class="btn btn-sm px-0 rounded-0 {{ Route::is('staff.ticket.view_ticket') ? 'active' : '' }}"
                                type="button">
                                Reply Threads
                            </a>
                            <a onclick="window.location='{{ route('staff.ticket.ticket_clarifications', $ticket->id) }}'"
                                class="btn btn-sm px-0 rounded-0 {{ Route::is('staff.ticket.ticket_clarifications') ? 'active' : '' }}"
                                type="button">
                                Clarifications
                            </a>
                            @endif
                        </div>
                    </div>
                    {{-- Replies/Comments --}}
                    @section('ticket-reply-clarifications')
                    @livewire('staff.ticket-replies', ['ticket' => $ticket])
                    @show
                    {{-- End Replies/Comments --}}
                </div>
                <div class="col-md-4">
                    <div class="container__ticket__details__right">
                        @livewire('staff.ticket.ticket-details', ['ticket' => $ticket])
                        @livewire('staff.ticket.ticket-actions', ['ticket' => $ticket])
                        @livewire('staff.ticket.ticket-tag', ['ticket' => $ticket])
                        @livewire('ticket-activity-logs', ['ticket' => $ticket])
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN)
    @livewire('staff.ticket.assign-ticket', ['ticket' => $ticket])
    @endif
    @livewire('staff.ticket.update-priority-level', ['ticket' => $ticket])

</div>

@if (Route::is('staff.ticket.view_ticket'))
@livewire('staff.ticket.reply-ticket', ['ticket' => $ticket])
@endif

@if (auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN)
@if (Route::is('staff.ticket.ticket_clarifications'))
@livewire('staff.ticket.send-clarification', ['ticket' => $ticket])
@endif
@livewire('staff.ticket.approve-ticket', ['ticket' => $ticket])
@livewire('staff.ticket.disapprove-ticket', ['ticket' => $ticket])
@endif

@livewire('staff.ticket.assign-tag', ['ticket' => $ticket])
@livewire('staff.ticket.close-ticket', ['ticket' => $ticket])
@include('layouts.staff.ticket.modal.preview_ticket_files_modal')

@endif
@endsection