@php
    use App\Models\Status;
    use App\Enums\ApprovalStatusEnum;
@endphp

@extends('layouts.staff.approver.base', ['title' => $ticket->subject])
@section('main-content')
    @livewire('approver.ticket.load-reason', ['ticket' => $ticket])
    <div class="row mx-0">
        <div class="card ticket__card" id="userTicketCard">
            <div class="ticket__details__section">
                <div class="d-flex flex-column details__card__top mb-3">
                    @livewire('approver.ticket.load-back-button-header', ['ticket' => $ticket])
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            @livewire('approver.ticket.load-ticket-status-header-text', ['ticket' => $ticket])
                            <h6 class="ticket__details__ticketnumber mb-0">Ticket: {{ $ticket->ticket_number }}</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2"
                            style="color: {{ $ticket->priorityLevel->color }} !important;">
                            <i class="bi bi-flag-fill"></i>
                            <p class="ticket__details__priority mb-0">{{ $ticket->priorityLevel->name }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between ticket__details__header flex-wrap">
                        <div class="mb-2">
                            <h6 class="ticket__details__title mb-0">{{ $ticket->subject }}</h6>
                            <small class="ticket__details__datetime">
                                {{ $ticket->dateCreated() }}, {{ $ticket->created_at->format('D') }} @
                                {{ $ticket->created_at->format('g:i A') }}
                            </small>
                        </div>
                        @livewire('approver.ticket.dropdown-approval-button', ['ticket' => $ticket])
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 position-relative mb-3">
                        @if ($isCostingAmountNeedCOOApproval)
                            @livewire('approver.ticket.ticket-costing', ['ticket' => $ticket])
                        @endif
                        <div class="card card__ticket__details border-0 p-0">
                            <div class="ticket__details__card__header d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-center user__account__media">
                                    @if ($ticket->user->profile->picture)
                                        <img src="{{ Storage::url($ticket->user->profile->picture) }}"
                                            class="image-fluid ticket__details__user__picture" alt="">
                                    @else
                                        <div class="user__name__initial d-flex align-items-center justify-content-center me-2 p-2 text-white"
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
                                @livewire('approver.ticket.recommendation-approval', ['ticket' => $ticket])
                                @if ($ticket->helpTopic->form)
                                    @livewire('approver.ticket.ticket-custom-form', ['ticket' => $ticket])
                                @endif
                                <div class="ticket__description">{!! $ticket->description !!}</div>
                                @if ($ticket->fileAttachments->isNotEmpty())
                                    <div class="ticket__attachments d-inline-flex mb-3 gap-1" data-bs-toggle="modal"
                                        data-bs-target="#requesterTicketFilesModalForm">
                                        <i class="fa-solid fa-file-zipper"></i>
                                        <small class="attachment__count">{{ $ticket->fileAttachments->count() }}</small>
                                        <small class="attachment__label">
                                            {{ $ticket->fileAttachments->count() > 1 ? 'file attachments' : 'file attachement' }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if (
                            $ticket->status_id !== Status::CLOSED ||
                                ($ticket->approval_status !== ApprovalStatusEnum::DISAPPROVED && $ticket->clarifications->count() !== 0))
                            {{-- Replies/Comments --}}
                            @livewire('approver.ticket-clarifications', ['ticket' => $ticket])
                        @else
                            <div class="row align-items-center bg-light rounded-3 mx-1 mb-4 mt-2 p-2 py-3">
                                <div class="col-md-12">
                                    <p class="mb-0" style="font-size: 13px; line-height: 19px;">
                                        Discussion with agent is disabled.
                                    </p>
                                </div>
                            </div>
                        @endif
                        {{-- End Replies/Comments --}}
                    </div>
                    <div class="col-lg-4">
                        <div class="container__ticket__details__right">
                            @livewire('approver.ticket.ticket-details', ['ticket' => $ticket])
                            @livewire('approver.ticket.ticket-level-approval', ['ticket' => $ticket])
                            @livewire('approver.ticket.ticket-logs', ['ticket' => $ticket])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('approver.ticket.approve-ticket', ['ticket' => $ticket])
    @livewire('approver.ticket.disapprove-ticket', ['ticket' => $ticket])
    @livewire('approver.ticket.send-clarification', ['ticket' => $ticket])
    @include('layouts.staff.approver.ticket.includes.modal.preview_ticket_files_modal')
@endsection
