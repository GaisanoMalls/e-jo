@extends('layouts.staff.base', ['title' => $ticket->subject])
@section('main-content')
    @livewire('staff.ticket.load-disapproval-reason', ['ticket' => $ticket])
    @if ($ticket)
        <div class="ticket__section" x-data="{ pinned: true }">
            <button :class="pinned ? 'd-none' : ''"
                class="btn btn-sm bg-blue d-flex align-items-center rounded-circle justify-content-center position-fixed p-0 text-white shadow"
                style="font-size: 1rem; height: 30px; width: 30px; top: 94px; right: 51px; z-index: 2; background-color: #D32839;" @click="pinned = true">
                <i class="bi bi-pin-angle-fill"></i>
            </button>
            <div class="row">
                <div class="col-xl-12 ticket__details__container">
                    <div class="ticket__details__top mb-3" x-show="pinned" x-transition.opacity.duration.300ms>
                        @livewire('staff.ticket.load-back-button-header', ['ticket' => $ticket])
                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                @livewire('staff.ticket.load-ticket-status-header-text', ['ticket' => $ticket])
                                <h6 class="ticket__details__ticketnumber mb-0">Ticket: {{ $ticket->ticket_number }}</h6>
                            </div>
                            <div class="d-flex align-items-center gap-4">
                                @livewire('staff.ticket.priority-level', ['ticket' => $ticket])
                                <button class="btn btn-sm btn__change__priority__level p-0"
                                    style="height: 30px; width: 30px; font-size: 1rem; color: #D32839;" @click="pinned = false">
                                    <i class="bi bi-pin-angle-fill"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between ticket__details__header flex-wrap gap-2">
                            <div class="mb-2">
                                <h6 class="ticket__details__title mb-0">{{ $ticket->subject }}</h6>
                                <small class="ticket__details__datetime">{{ $ticket->dateCreated() }},
                                    {{ $ticket->created_at->format('D') }} @
                                    {{ $ticket->created_at->format('g:i A') }}
                                </small>
                            </div>
                            <div class="d-flex justify-content-center gap-lg-4 gap-xl-4 flex-wrap gap-3">
                                @if ($ticket->isSpecialProject() && auth()->user()->isAgent())
                                    @livewire('staff.ticket.load-costing-button-header', ['ticket' => $ticket])
                                @endif
                                @if (Route::is('staff.ticket.view_ticket'))
                                    @livewire('staff.ticket.load-reply-button-header', ['ticket' => $ticket])
                                @endif
                                @if (Route::is('staff.ticket.ticket_clarifications') && auth()->user()->isServiceDepartmentAdmin())
                                    @livewire('staff.ticket.load-clarify-ticket-button-header', ['ticket' => $ticket])
                                @endif
                                @if (auth()->user()->isAgent())
                                    @livewire('staff.ticket.claim-ticket', ['ticket' => $ticket])
                                @endif
                                @livewire('staff.ticket.load-reopen-ticket-button-header', ['ticket' => $ticket])
                                @livewire('staff.ticket.load-close-status-button-header', ['ticket' => $ticket])
                                @if (auth()->user()->isServiceDepartmentAdmin())
                                    @livewire('staff.ticket.dropdown-approval-button', ['ticket' => $ticket])
                                @endif
                                @livewire('staff.ticket.bookmark-ticket', ['ticket' => $ticket])
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 position-relative">
                            @livewire('staff.ticket.ticket-costing', ['ticket' => $ticket])
                            <div class="card card__ticket__details border-0 p-0">
                                @if ($requester)
                                    <div class="ticket__details__card__header d-flex justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center user__account__media">
                                            @if ($requester->profile->picture)
                                                <img src="{{ Storage::url($requester->profile->picture) }}"
                                                    class="image-fluid ticket__details__user__picture" alt="">
                                            @else
                                                <div class="user__name__initial d-flex align-items-center justify-content-center me-2 p-2 text-white"
                                                    style="background-color: #24695C;">
                                                    {{ $requester->profile->getNameInitial() }}
                                                </div>
                                            @endif
                                            <div class="d-flex flex-column">
                                                <small class="ticket__details__user__fullname">
                                                    <span>{{ $requester->profile->getFullName }}</span>
                                                    @if ($requester->trashed())
                                                        <i class="bi bi-exclamation-circle-fill text-danger ms-1" style="font-size: 0.7rem;"
                                                            data-tooltip="Archived" data-tooltip-position="right" data-tooltip-font-size="0.7rem"
                                                            data-tooltip-max-width="200px" data-tooltip-additional-classes="rounded-3"></i>
                                                    @endif
                                                </small>
                                                <small class="ticket__details__user__department">
                                                    {{ $requester->getBUDepartments() }} -
                                                    {{ $requester->getBranches() }}
                                                </small>
                                            </div>
                                        </div>
                                        <small class="ticket__details__time mt-2">
                                            {{ $ticket->created_at->diffForHumans(null, true) }} ago
                                        </small>
                                    </div>
                                @endif
                                <div class="ticket__details__card__body">
                                    @livewire('staff.ticket.recommendation-approval', ['ticket' => $ticket])
                                    @if ($ticket->helpTopic?->form)
                                        @livewire('staff.ticket.ticket-custom-form', ['ticket' => $ticket])
                                    @else
                                        <div class="ticket__description mt-3">{!! $ticket->description !!}</div>
                                    @endif
                                    @if ($ticket->fileAttachments->count() > 0)
                                        <div class="ticket__attachments d-inline-flex mb-3 gap-1" data-bs-toggle="modal"
                                            data-bs-target="#ticketFilesModalForm">
                                            <i class="fa-solid fa-file-zipper"></i>
                                            <small class="attachment__count">{{ $ticket->fileAttachments->count() }}</small>
                                            <small class="attachment__label">
                                                {{ $ticket->fileAttachments->count() > 1 ? 'file attachments' : 'file attachement' }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4 mt-4">
                                <small class="ticket__discussions text-muted">
                                    @section('count-replyThreads-clarificafions')
                                        @livewire('staff.ticket.load-ticket-discussion-count', ['ticket' => $ticket])
                                    @show
                                </small>
                                <div class="d-flex align-items-center threads__clarifications__tab__container gap-3">
                                    @if (auth()->user()->isServiceDepartmentAdmin())
                                        <a onclick="window.location='{{ route('staff.ticket.view_ticket', $ticket->id) }}'"
                                            class="btn btn-sm rounded-0 {{ Route::is('staff.ticket.view_ticket') ? 'active' : '' }} px-0" type="button">
                                            Reply Threads
                                        </a>
                                        <a onclick="window.location='{{ route('staff.ticket.ticket_clarifications', $ticket->id) }}'"
                                            class="btn btn-sm rounded-0 {{ Route::is('staff.ticket.ticket_clarifications') ? 'active' : '' }} px-0"
                                            type="button">
                                            Clarifications
                                        </a>
                                    @endif
                                    <a onclick="window.location='{{ route('staff.ticket.ticket_subtasks', $ticket->id) }}'"
                                        class="btn btn-sm rounded-0 {{ Route::is('staff.ticket.ticket_subtasks') ? 'active' : '' }} px-0" type="button">
                                        Subtasks
                                    </a>
                                </div>
                            </div>
                            {{-- Replies/Comments --}}
                            @section('ticket-reply-clarifications-subtasks')
                                @livewire('staff.ticket-replies', ['ticket' => $ticket])
                            @show
                            {{-- End Replies/Comments --}}
                        </div>
                        <div class="col-lg-4">
                            <div class="container__ticket__details__right">
                                @livewire('staff.ticket.ticket-details', ['ticket' => $ticket])
                                @livewire('staff.ticket.ticket-level-approval', ['ticket' => $ticket])
                                @livewire('staff.ticket.ticket-actions', ['ticket' => $ticket])
                                @livewire('staff.ticket.ticket-tag', ['ticket' => $ticket])
                                @livewire('ticket-activity-logs', ['ticket' => $ticket])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($ticket->isSpecialProject() && auth()->user()->isAgent())
                @livewire('staff.ticket.add-costing', ['ticket' => $ticket])
            @endif
            @if (auth()->user()->isServiceDepartmentAdmin() || auth()->user()->isAgent())
                @livewire('staff.ticket.assign-ticket', ['ticket' => $ticket])
            @endif
            @if (auth()->user()->isServiceDepartmentAdmin())
                @livewire('staff.ticket.request-approval', ['ticket' => $ticket])
            @endif
            @livewire('staff.ticket.update-priority-level', ['ticket' => $ticket])
        </div>
        @if (Route::is('staff.ticket.view_ticket'))
            @livewire('staff.ticket.reply-ticket', ['ticket' => $ticket])
        @endif
        @if (auth()->user()->isServiceDepartmentAdmin())
            @if (Route::is('staff.ticket.ticket_clarifications'))
                @livewire('staff.ticket.send-clarification', ['ticket' => $ticket])
            @endif
            @livewire('staff.ticket.approve-ticket', ['ticket' => $ticket])
            @livewire('staff.ticket.disapprove-ticket', ['ticket' => $ticket])
        @endif
        @livewire('staff.ticket.assign-tag', ['ticket' => $ticket])
        @livewire('staff.ticket.close-ticket', ['ticket' => $ticket])
        @if (auth()->user()->isServiceDepartmentAdmin() || auth()->user()->isAgent())
            @livewire('staff.ticket.reopen-ticket', ['ticket' => $ticket])
        @endif
        @include('layouts.staff.ticket.modal.preview_ticket_files_modal')
    @endif
@endsection
