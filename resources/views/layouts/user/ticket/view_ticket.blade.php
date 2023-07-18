@extends('layouts.user.base', ['title' => $ticket->subject ])

@section('main-content')
<div class="row mx-0">
    <div class="card ticket__card" id="userTicketCard">
        <div class="ticket__details__section">
            <div class="mb-3 d-flex flex-column details__card__top">
                @switch($ticket->status->id)
                @case(App\Models\Status::ON_PROCESS)
                <a href="{{ route('user.tickets.on_process_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @case(App\Models\Status::OPEN)
                <a href="{{ route('user.tickets.open_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @case(App\Models\Status::CLOSED)
                <a href="{{ route('user.tickets.closed_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @endswitch
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
                            <small
                                class="ticket__details__time mt-2">{{ $ticket->created_at->diffForHumans(null, true) }}</small>
                        </div>
                        <div class="ticket__details__card__body">
                            <div class="ticket__description">{!! $ticket->description !!}</div>
                            @if ($ticket->fileAttachments->count() > 0)
                            <div class="ticket__attachments d-inline-flex gap-1 mb-3" data-bs-toggle="modal"
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
                    <div class="mb-2">
                        <small class="ticket__discussions">
                            {{ $ticket->replies->count() }}
                            {{ $ticket->replies->count() > 1 ? 'Discussions' : 'Discussion' }}
                        </small>
                    </div>
                    {{-- Replies/Comments --}}
                    @include('layouts.user.ticket.includes.ticket_replies')
                    <button type="button"
                        class="btn btn__reply__ticket btn__reply__ticket__mobile mb-4 d-flex align-items-center justify-content-center gap-2"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasRequesterReplyTicketForm"
                        aria-controls="offcanvasBottom">
                        <i class="fa-solid fa-pen"></i>
                        <span class="lbl__reply">Reply</span>
                    </button>
                    {{-- End Replies/Comments --}}
                </div>
                <div class="col-md-4">
                    <div class="container__ticket__details__right">
                        @include('layouts.user.ticket.includes.ticket_details')
                        @include('layouts.user.ticket.includes.ticket_assigned_agent')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.user.ticket.includes.modal.preview_ticket_files_modal')
@include('layouts.user.ticket.includes.offcanvas.reply_ticket_offcanvas')
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
