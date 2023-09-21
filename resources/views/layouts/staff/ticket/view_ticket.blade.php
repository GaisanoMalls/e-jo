@extends('layouts.staff.base', ['title' => $ticket->subject])

@section('main-content')
@if ($ticket)
<div class="ticket__section">
    <div class="row">
        <div class="col-xl-12 ticket__details__container">
            <div class="mb-3 ticket__details__top">
                @if($ticket->status->id == App\Models\Status::APPROVED)
                @if (auth()->user()->role_id == App\Models\Role::AGENT)
                <a href="{{ route('staff.tickets.open_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @else
                <a href="{{ route('staff.tickets.approved_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @endif
                @endif
                @if($ticket->status->id == App\Models\Status::OPEN)
                <a href="{{ route('staff.tickets.open_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @endif
                @if($ticket->status->id == App\Models\Status::CLAIMED)
                <a href="{{ route('staff.tickets.claimed_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @endif
                @if($ticket->status->id == App\Models\Status::ON_PROCESS)
                <a href="{{ route('staff.tickets.on_process_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @endif
                @if($ticket->status->id == App\Models\Status::VIEWED)
                <a href="{{ route('staff.tickets.viewed_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @endif
                @if($ticket->status->id == App\Models\Status::OVERDUE)
                <a href="{{ route('staff.tickets.overdue_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @endif
                @if($ticket->status->id == App\Models\Status::CLOSED && $ticket->approval_status ==
                App\Models\ApprovalStatus::DISAPPROVED)
                <a href="{{ route('staff.tickets.disapproved_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @endif
                @if($ticket->status->id == App\Models\Status::CLOSED || $ticket->approval_status ==
                App\Models\ApprovalStatus::APPROVED && $ticket->approval_status ==
                App\Models\ApprovalStatus::DISAPPROVED)
                <a href="{{ route('staff.tickets.closed_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @endif

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <p class="mb-0 ticket__details__status">{{ $ticket->status->name }}</p>
                        <h6 class="ticket__details__ticketnumber mb-0">Ticket: {{ $ticket->ticket_number }}</h6>
                    </div>
                    @livewire('staff.ticket.priority-level', ['ticket' => $ticket])
                </div>
                <div class="d-flex flex-wrap justify-content-between ticket__details__header">
                    <div class="mb-2">
                        <h6 class="ticket__details__title mb-0">{{ $ticket->subject }}</h6>
                        <small class="ticket__details__datetime">{{ $ticket->dateCreated() }},
                            {{ $ticket->created_at->format('D') }} @ {{ $ticket->created_at->format('g:i A') }}</small>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center gap-3 gap-lg-4 gap-xl-4">

                        @if (auth()->user()->role_id == App\Models\Role::AGENT)
                        {{-- SHOW "Claim" BUTTON FOR AGENT USER ONLY --}}
                        <div class="d-flex flex-column">
                            @if ($ticket->status_id == App\Models\Status::CLAIMED)
                            <button style="background-color: {{ $ticket->status->color }} !important;"
                                class="btn btn-sm border-0 m-auto text-white ticket__detatails__btn__claim claimed d-flex  align-items-center justify-content-center">
                                <i class="fa-solid fa-flag"></i>
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
                                    <i class='bx bxs-flag-alt' style="font-size: 17px;"></i>
                                </button>
                                <small class="ticket__details__topbuttons__label">Claim</small>
                            </form>
                            @else
                            <button style="background-color: #FF8B8B !important;"
                                class="btn btn-sm border-0 m-auto text-white ticket__detatails__btn__claim claimed d-flex  align-items-center justify-content-center">
                                <i class="fa-solid fa-flag"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label fw-bold">Claimed</small>
                            @endif
                            @endif
                        </div>
                        @elseif ($ticket->status_id == App\Models\Status::CLAIMED)
                        <div class="d-flex flex-column">
                            <button style="background-color: {{ $ticket->status->color }} !important;" class="btn btn-sm border-0 m-auto text-white ticket__detatails__btn__claim claimed d-flex
                                align-items-center justify-content-center">
                                <i class="fa-solid fa-flag"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label fw-bold">Claimed</small>
                        </div>
                        @endif

                        @if ($ticket->status_id == App\Models\Status::CLOSED)
                        <div class="d-flex flex-column">
                            <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__close closed d-flex text-white
                                align-items-center justify-content-center"
                                style="background-color: {{ $ticket->status->color }} !important;">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label fw-bold">Closed</small>
                        </div>
                        @else
                        <div class="d-flex flex-column">
                            <form action="{{ route('staff.ticket.close_ticket', $ticket->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex
                                    align-items-center justify-content-center" type="submit">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                            <small class="ticket__details__topbuttons__label">Close</small>
                        </div>
                        @endif
                        {{-- <div class="d-flex flex-column">
                            <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__bookmark d-flex
                                align-items-center justify-content-center" type="submit">
                                <i class="fa-solid fa-bookmark"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label">Bookmark</small>
                        </div> --}}
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
                    <div class="mb-2 mt-4">
                        <small class="ticket__discussions text-muted">
                            {{ $ticket->replies->count() > 1 ? 'Discussions' : 'Discussion' }}
                            ({{ $ticket->replies->count() }})
                        </small>
                    </div>
                    {{-- Replies/Comments --}}
                    @livewire('staff.ticket-replies', ['ticket' => $ticket])

                    @if ($ticket->status_id != App\Models\Status::CLOSED)
                    <button type="button"
                        class="btn btn__reply__ticket btn__reply__ticket__mobile mb-4 mt-5 d-flex align-items-center justify-content-center gap-2"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasReplyTicketForm"
                        aria-controls="offcanvasBottom">
                        <i class="fa-solid fa-pen"></i>
                        <span class="lbl__reply">Reply</span>
                    </button>
                    @endif
                    {{-- End Replies/Comments --}}
                </div>
                <div class="col-md-4">
                    <div class="container__ticket__details__right">
                        @livewire('staff.ticket.ticket-details', ['ticket' => $ticket])
                        @if (auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN && $ticket->status_id
                        != App\Models\Status::CLOSED)
                        <div class="card border-0 p-0 card__ticket__details">
                            <div class="d-flex flex-column gap-3 ticket__details__card__body__right">
                                <label class="ticket__actions__label">Ticket Actions</label>
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <ul class="mb-0 ticket__actions" style="padding-left: 1.1rem;">
                                        <li>
                                            <small>Move this ticket to other team.</small>
                                        </li>
                                        <li>
                                            <small>Assign this ticket to other agent.</small>
                                        </li>
                                    </ul>
                                    <button class="btn btn-block bg-dark btn__ticket__set__action"
                                        data-bs-toggle="modal" data-bs-target="#assignTicketModal">
                                        Set Action
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
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
{{-- @include('layouts.staff.ticket.modal.reply_ticket_modal')--}}
@livewire('staff.ticket.assign-tag', ['ticket' => $ticket])
@include('layouts.staff.ticket.modal.preview_ticket_files_modal')
@include('layouts.staff.ticket.offcanvas.reply_ticket_offcanvas')
@endif
@endsection

@if ($errors->storeTicketReply->any())
@push('modal-with-error')
<script>
    $(function () {
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasReplyTicketForm'));
        offcanvas.show();
    });

</script>
@endpush
@endif