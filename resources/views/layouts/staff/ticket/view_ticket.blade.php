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

                <div class="d-flex align-items-center gap-3 mb-4">
                    <p class="mb-0 ticket__details__status">{{ $ticket->status->name }}</p>
                    <p class="mb-0 ticket__details__priority">{{ $ticket->priorityLevel->name }}</p>
                    <h6 class="ticket__details__ticketnumber mb-0">Ticket: {{ $ticket->ticket_number }}</h6>
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
                        <div class="card border-0 p-0 card__ticket__details">
                            <div class="ticket__details__card__body__right">
                                <div class="mb-3">
                                    <label class="ticket__actions__label">Ticket Details</label>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label" style="font-weight: 500;">
                                            Approval status:
                                        </small>
                                        <small class="ticket__details__info">
                                            @if ($ticket->approval_status == App\Models\ApprovalStatus::APPROVED)
                                            <i class="fa-solid fa-circle-check me-1"
                                                style="color: green; font-size: 11px;"></i>
                                            Approved
                                            @elseif ($ticket->approval_status ==
                                            App\Models\ApprovalStatus::FOR_APPROVAL)
                                            <i class="fa-solid fa-paper-plane me-1"
                                                style="color: orange; font-size: 11px;"></i>
                                            For Approval
                                            @elseif ($ticket->approval_status ==
                                            App\Models\ApprovalStatus::DISAPPROVED)
                                            <i class="fa-solid fa-xmark me-1" style="color: red; font-size: 11px;"></i>
                                            Disapproved
                                            @else
                                            ----
                                            @endif
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label"
                                            style="font-weight: 500;">Branch:</small>
                                        <small class="ticket__details__info">
                                            <i class="fa-solid fa-location-dot me-1 text-muted"
                                                style="font-size: 11px;"></i>
                                            {{ $ticket->branch->name }}
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label" style="font-weight: 500;">
                                            Service department:</small>
                                        <small class="ticket__details__info">
                                            <i class="fa-solid fa-gears me-1 text-muted" style="font-size: 11px;"></i>
                                            {{ $ticket->serviceDepartment->name }}
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label"
                                            style="font-weight: 500;">Team:</small>
                                        <small class="ticket__details__info">
                                            <i class="fa-solid fa-people-group me-1 text-muted"
                                                style="font-size: 11px;"></i>
                                            {{ $ticket->team->name ?? '----' }}
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label" style="font-weight: 500;">
                                            Help topic:
                                        </small>
                                        <small class="ticket__details__info">
                                            <i class="bi bi-question-circle-fill me-1 text-muted"
                                                style="font-size: 11px;"></i>
                                            {{ $ticket->helpTopic->name }}
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label" style="font-weight: 500;">
                                            Assigned agent:
                                        </small>
                                        <small
                                            class="ticket__details__info {{ $ticket->agent_id !== null ? '' : 'not__set'}}">
                                            <i class="fa-solid fa-user-check me-1 text-muted"
                                                style="font-size: 11px;"></i>
                                            {{ $ticket->agent_id !== null
                                            ? $ticket->agent->profile->getFullName()
                                            : '----' }}
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label" style="font-weight: 500;">
                                            SLA:</small>
                                        <small class="ticket__details__info">
                                            <i class="fa-solid fa-clock me-1 text-muted" style="font-size: 11px;"></i>
                                            {{ $ticket->sla->time_unit ?? '----' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN)
                        <div class="card border-0 p-0 card__ticket__details">
                            <div class="ticket__details__card__body__right">
                                <div class="mb-3">
                                    <label class="ticket__actions__label">Ticket Actions</label>
                                    <ul class="ticket__actions">
                                        <li>
                                            <small>
                                                Set ticket priority:
                                                <span class="fw-semi-bold">
                                                    <span class="priority__name">Low,</span>
                                                    <span class="priority__name">Medium,</span>
                                                    <span class="priority__name">High</span>
                                                    <span>and</span>
                                                    <span class="priority__name">Urgent</span>
                                                </span>
                                            </small>
                                        </li>
                                        <li>
                                            <small>
                                                Assign this ticket to other agent.
                                            </small>
                                        </li>
                                    </ul>
                                </div>
                                <button class="btn btn-block bg-dark btn__ticket__set__action" data-bs-toggle="modal"
                                    data-bs-target="#ticketActionModalForm">
                                    Set Action
                                </button>
                            </div>
                        </div>
                        @endif
                        <div class="card border-0 p-0 card__ticket__details card__ticket__details__right">
                            <div class="ticket__details__card__body__right">
                                <div class="mb-3 d-flex justify-content-between">
                                    <small class="ticket__actions__label">Tags</small>
                                    <a type="button" class="btn__add__tags" data-bs-toggle="modal"
                                        data-bs-target="#ticketTagModalForm">
                                        <i class="fa-solid fa-plus"></i>
                                        Add
                                    </a>
                                </div>
                                <a href="" class="btn btn-sm ticket__tag">System Issue</a>
                                <a href="" class="btn btn-sm ticket__tag">BBLMS Account</a>
                            </div>
                        </div>
                        <div class="card border-0 p-0 card__ticket__details card__ticket__details__right">
                            <div class="ticket__details__card__body__right log__container">
                                <div class="mb-3 d-flex justify-content-between">
                                    <small class="ticket__actions__label">Ticket Activity Logs</small>
                                </div>
                                <div class="d-flex flex-column">
                                    @foreach ( $ticket->activityLogs as $log)
                                    <div class="d-flex justify-content-between py-3 log__list
                                        {{ $ticket->activityLogs->count() > 1 ? 'border-bottom' : '' }}">
                                        <div class="d-flex gap-3">
                                            <i class="bi bi-clock-history log__icon"></i>
                                            <div class="d-flex align-items-start flex-column">
                                                <h6 class="mb-1 log__description">
                                                    <strong class="causer__details">
                                                        {{ $log->causerDetails() }}
                                                    </strong>
                                                    {{ $log->description }}
                                                </h6>
                                                <small class="log__date">
                                                    {{ $log->dateCreated() }}
                                                </small>
                                            </div>
                                        </div>
                                        <small class="log__time">
                                            {{ $log->created_at->diffForHumans(null, true) }}
                                            ago
                                        </small>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN)
    @include('layouts.staff.ticket.modal.ticket_actions_modal')
    @endif
</div>
{{-- @include('layouts.staff.ticket.modal.reply_ticket_modal')--}}
@include('layouts.staff.ticket.modal.ticket_tag_modal')
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