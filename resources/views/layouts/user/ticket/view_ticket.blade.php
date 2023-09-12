@extends('layouts.user.base', ['title' => $ticket->subject ])

@section('main-content')
@if ($reason)
@include('layouts.user.ticket.includes.modal.reason')
@endif
@if ($ticket->approval_status === App\Models\ApprovalStatus::DISAPPROVED)
<div class="alert alert-warning p-3 rounded-3 border-0 mt-4 mb-3" role="alert" style="font-size: 13px;">
    <div class="mb-2">We regret to inform you that the approver has disapproved your ticket. After careful
        consideration, the
        decision has been made not to proceed with the requested action at this time.
        <br>
        Please feel free to reach out
        to the approver or the relevant team if you have any questions or require further
        clarification on the disapproval decision. They will be more than willing to assist you with any concerns
        you may have.
    </div>
    @if ($reason)
    <button type="button" class="btn btn-sm p-0 d-flex align-items-center rounded-0 border-0 gap-1 btn__see__reason"
        data-bs-toggle="modal" data-bs-target="#reasonModal">
        See reason of disapproval
    </button>
    @endif
</div>
@endif
<div class="row mx-0">
    <div class="card ticket__card" id="userTicketCard">
        <div class="ticket__details__section">
            <div class="mb-3 d-flex flex-column details__card__top">
                @switch($ticket->status_id)
                @case(App\Models\Status::OPEN)
                <a href="{{ route('user.tickets.open_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @case(App\Models\Status::ON_PROCESS)
                <a href="{{ route('user.tickets.on_process_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @case(App\Models\Status::VIEWED)
                <a href="{{ route('user.tickets.viewed_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @case(App\Models\Status::APPROVED)
                <a href="{{ route('user.tickets.approved_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @endswitch
                @if ($ticket->approval_status === App\Models\ApprovalStatus::DISAPPROVED)
                <a href="{{ url()->previous() }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @endif
                @if ($ticket->status_id === App\Models\Status::CLOSED)
                <a href="{{ url()->previous() }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @endif
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <p class="mb-0 ticket__details__status">{{ $ticket->status->name }}</p>
                        <h6 class="ticket__details__ticketnumber mb-0">Ticket: {{ $ticket->ticket_number }}</h6>
                    </div>
                    <p class="mb-0 ticket__details__priority">{{ $ticket->priorityLevel->name }}</p>
                </div>
                <div class="d-flex flex-wrap justify-content-between ticket__details__header">
                    <div class="mb-2">
                        <h6 class="ticket__details__title mb-0">{{ $ticket->subject }}</h6>
                        <small class="ticket__details__datetime">{{ $ticket->dateCreated() }},
                            {{ $ticket->created_at->format('D') }} @ {{ $ticket->created_at->format('g:i A') }}</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 position-relative">
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
                                {{ $ticket->created_at->diffForHumans(null, true) }}
                                ago
                            </small>
                        </div>
                        <div class="ticket__details__card__body">
                            <div class="ticket__description">{!! $ticket->description !!}</div>
                            @if ($ticket->fileAttachments->count() > 0)
                            <div class="ticket__attachments d-inline-flex gap-1 mb-3" data-bs-toggle="modal"
                                data-bs-target="#requesterTicketFilesModalForm">
                                <i class="fa-solid fa-file-zipper"></i>
                                <small class="attachment__count">{{ $ticket->fileAttachments->count() }}</small>
                                <small class="attachment__label">
                                    {{ $ticket->fileAttachments->count() > 1
                                    ? 'file attachments'
                                    : 'file attachment'
                                    }}
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4 mt-4 d-flex align-items-center justify-content-between">
                        <small class="ticket__discussions text-muted">
                            @section('count-replyThraeds-clarificafions')
                            {{ $ticket->replies->count() > 1 ? 'Discussions' : 'Discussion' }}
                            ({{ $ticket->replies->count() }})
                            @show
                        </small>
                        <div class="d-flex align-items-center gap-3 threads__clarifications__tab__container">
                            <a onclick="window.location='{{ route('user.ticket.view_ticket', $ticket->id) }}'"
                                class="btn btn-sm px-0 rounded-0 {{ Route::is('user.ticket.view_ticket') ? 'active' : '' }}"
                                type="button">
                                Reply Threads
                            </a>
                            <a onclick="window.location='{{ route('user.ticket.ticket_clarifications', $ticket->id) }}'"
                                class="btn btn-sm px-0 rounded-0 {{ Route::is('user.ticket.ticket_clarifications') ? 'active' : '' }}"
                                type="button">
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
                <div class="col-md-5">
                    <div class="container__ticket__details__right">
                        @include('layouts.user.ticket.includes.ticket_details')
                        @include('layouts.user.ticket.includes.ticket_assigned_agent')
                        {{-- @include('layouts.user.ticket.includes.approvals') --}}
                        @include('layouts.user.ticket.includes.ticket_activity_logs')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.user.ticket.includes.modal.preview_ticket_files_modal')
@include('layouts.user.ticket.includes.offcanvas.reply_ticket_offcanvas')
@include('layouts.user.ticket.includes.offcanvas.reply_clarification_offcanvas')
@endsection

@if ($errors->requesterStoreTicketReply->any())
@push('offcanvas-error')
<script>
    $(function () {
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasRequesterReplyTicketForm'));
        offcanvas.show();
    });

</script>
@endpush
@endif

@if ($errors->storeTicketReplyClarification->any())
@push('offcanvas-error')
<script>
    $(function () {
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasRequesterReplyTicketClarificationForm'));
        offcanvas.show();
    });

</script>
@endpush
@endif