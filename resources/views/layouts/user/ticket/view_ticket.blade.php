@extends('layouts.user.base', ['title' => $ticket->subject])

@section('main-content')
    @livewire('requester.ticket.reason-of-disapproval', ['ticket' => $ticket])
    <div class="row mx-0">
        <div class="card ticket__card" id="userTicketCard">
            <div class="ticket__details__section">
                <div class="mb-3 d-flex flex-column details__card__top">
                    @livewire('requester.ticket.load-back-button-header', ['ticket' => $ticket])
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            @livewire('requester.ticket.load-ticket-status-header-text', ['ticket' => $ticket])
                            <h6 class="ticket__details__ticketnumber mb-0">Ticket: {{ $ticket->ticket_number }}</h6>
                        </div>
                        <p class="mb-0 ticket__details__priority">{{ $ticket->priorityLevel->name }}</p>
                    </div>
                    <div class="d-flex flex-wrap justify-content-between align-items-center ticket__details__header">
                        <div class="mb-2">
                            <h6 class="ticket__details__title mb-0">{{ $ticket->subject }}</h6>
                            <small class="ticket__details__datetime">
                                {{ $ticket->dateCreated() }},
                                {{ $ticket->created_at->format('D') }} @ {{ $ticket->created_at->format('g:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 position-relative">
                        @if ($isCostingAmountNeedApproval)
                            @livewire('requester.ticket.ticket-costing', ['ticket' => $ticket])
                        @endif
                        <div class="card border-0 p-0 card__ticket__details">
                            <div class="ticket__details__card__header d-flex flex-wrap justify-content-between">
                                <div class="d-flex align-items-center user__account__media">
                                    @if ($ticket->user->profile->picture)
                                        <img src="{{ Storage::url($ticket->user->profile->picture) }}" class="image-fluid ticket__details__user__picture"
                                            alt="">
                                    @else
                                        <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                    text-white"
                                            style="background-color: #24695C;">
                                            {{ $ticket->user->profile->getNameInitial() }}</div>
                                    @endif
                                    <div class="d-flex flex-column">
                                        <small class="ticket__details__user__fullname">
                                            <span>{{ $ticket->user->profile->getFullName }}</span>
                                        </small>
                                        <small class="ticket__details__user__department">
                                            {{ $ticket->user->getBUDepartments() }} - {{ $ticket->user->getBranches() }}
                                        </small>
                                    </div>
                                </div>
                                <small class="ticket__details__time mt-2">
                                    {{ $ticket->created_at->diffForHumans(null, true) }}
                                    ago
                                </small>
                            </div>
                            <div class="ticket__details__card__body">
                                @livewire('requester.ticket.recommendation-approval', ['ticket' => $ticket])
                                @if ($ticket->helpTopic?->form)
                                    @livewire('requester.ticket.ticket-custom-form', ['ticket' => $ticket])
                                @else
                                    <div class="ticket__description">{!! $ticket->description !!}</div>
                                @endif
                                @if ($ticket->fileAttachments->count() > 0)
                                    <div class="ticket__attachments d-inline-flex gap-1 mb-3" data-bs-toggle="modal"
                                        data-bs-target="#requesterTicketFilesModalForm">
                                        <i class="fa-solid fa-file-zipper"></i>
                                        <small class="attachment__count">{{ $ticket->fileAttachments->count() }}</small>
                                        <small class="attachment__label">
                                            {{ $ticket->fileAttachments->count() > 1 ? 'file attachments' : 'file attachment' }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mb-4 mt-4 d-flex align-items-center justify-content-between">
                            <small class="ticket__discussions text-muted">
                            @section('count-replyThreads-clarificafions')
                                @livewire('requester.ticket.load-discussions-count', ['ticket' => $ticket])
                            @show
                        </small>
                        <div class="d-flex align-items-center gap-3 threads__clarifications__tab__container">
                            <a onclick="window.location='{{ route('user.ticket.view_ticket', $ticket->id) }}'"
                                class="btn btn-sm px-0 rounded-0 position-relative {{ Route::is('user.ticket.view_ticket') ? 'active' : '' }}"
                                type="button">
                                @livewire('ticket-notif.new-reply-icon', ['ticket' => $ticket])
                                Reply Threads
                            </a>
                            <a onclick="window.location='{{ route('user.ticket.ticket_clarifications', $ticket->id) }}'"
                                class="btn btn-sm px-0 rounded-0 position-relative {{ Route::is('user.ticket.ticket_clarifications') ? 'active' : '' }}"
                                type="button">
                                @livewire('ticket-notif.new-clarification-icon', ['ticket' => $ticket])
                                Clarifications
                            </a>
                        </div>
                    </div>
                    {{-- Replies/Comments --}}
                    @section('ticket-reply-clarifications')
                        @livewire('requester.ticket-replies', ['ticket' => $ticket])
                    @show
                    {{-- End Replies/Comments --}}
                </div>
                <div class="col-lg-4">
                    <div class="container__ticket__details__right">
                        @livewire('requester.ticket.ticket-details', ['ticket' => $ticket])
                        @livewire('requester.ticket.ticket-level-approval', ['ticket' => $ticket])
                        @livewire('requester.ticket.assigned-agent', ['ticket' => $ticket])
                        @livewire('requester.ticket.ticket-logs', ['ticket' => $ticket])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.user.ticket.includes.modal.preview_ticket_files_modal')
@livewire('requester.ticket.send-ticket-reply', ['ticket' => $ticket])
@livewire('requester.ticket.send-clarification', ['ticket' => $ticket])
@endsection
