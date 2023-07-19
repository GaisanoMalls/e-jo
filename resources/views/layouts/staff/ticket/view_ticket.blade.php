@extends('layouts.staff.base', ['title' => $ticket->subject])

@section('main-content')
@if ($ticket)
<div class="ticket__section">
    <div class="row">
        <div class="col-xl-12 ticket__details__container">
            <div class="mb-3 ticket__details__top">
                @switch($ticket->status->id)
                @case(App\Models\Status::ON_PROCESS)
                <a href="{{ route('staff.tickets.on_process_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @case(App\Models\Status::OPEN)
                <a href="{{ route('staff.tickets.open_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @case(App\Models\Status::APPROVED)
                <a href="{{ route('staff.tickets.approved_tickets') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @endswitch
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
                        <div class="d-flex flex-column">
                            <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__bookmark d-flex
                                            align-items-center justify-content-center" type="submit">
                                <i class="fa-solid fa-bookmark"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label">Bookmark</small>
                        </div>
                        <div class="d-flex flex-column">
                            <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__markasresolved d-flex
                                            align-items-center justify-content-center" type="submit">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label">Close</small>
                        </div>
                        <div class="d-flex flex-column">
                            <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__transfer d-flex
                                            align-items-center justify-content-center" type="submit">
                                <i class="fa-solid fa-flag"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label">Claim</small>
                        </div>
                        <div class="d-flex flex-column">
                            <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__transfer d-flex
                                            align-items-center justify-content-center" type="submit">
                                <i class="fa-solid fa-print"></i>
                            </button>
                            <small class="ticket__details__topbuttons__label">Print</small>
                        </div>
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
                    <div class="mb-2 mt-4">
                        <small class="ticket__discussions text-muted">
                            {{ $ticket->replies->count() > 1 ? 'Discussions' : 'Discussion' }}
                            ({{ $ticket->replies->count() }})
                        </small>
                    </div>
                    {{-- Replies/Comments --}}
                    @if ($ticket->replies->count() > 0)
                    @foreach ($ticket->replies as $reply)
                    @include('layouts.staff.ticket.modal.preview_reply_ticket_files_modal')
                    <div class="card border-0 p-0 card__ticket__details"
                        style="width: fit-content; max-width: 70%;
                        {{ $reply->user_id === auth()->user()->id ? 'background-color: #D0F0F7; margin-left: auto;' : 'background-color: #E9ECEF; margin-right: auto;' }}">
                        <div
                            class="ticket__details__card__header d-flex pb-0 align-items-center justify-content-between">
                            <div class="d-flex align-items-center w-100">
                                @if ($reply->user->id !== auth()->user()->id)
                                @if ($reply->user->profile->picture)
                                <img src="{{ Storage::url($reply->user->profile->picture) }}" alt="" class="image-fluid ticket__details__user__picture
                                        reply__ticket__details__user__picture">
                                @else
                                <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                    text-white" style="background-color: #24695C;">
                                    {{ $reply->user->profile->getNameInitial() }}</div>
                                @endif
                                @endif
                                <div class="d-flex flex-wrap justify-content-between w-100">
                                    @if ($reply->user->id !== auth()->user()->id)
                                    <small
                                        class="pe-3 ticket__details__user__fullname reply__ticket__details__user__fullname">
                                        {{ $reply->user->profile->getFullName() }}
                                        {{ $reply->user_id === auth()->user()->id ? '(me)' : '' }}
                                    </small>
                                    @else
                                    <small class="pe-3 text-muted" style="font-size: 12px;">Sent</small>
                                    @endif
                                    <small
                                        class="ticket__details__time">{{ $reply->created_at->diffForHumans(null, true) }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="ticket__details__card__body pb-3">
                            <div class="ticket__reply__content">
                                <div class="ticket__description reply__ticket__description">{!! $reply->description !!}
                                </div>
                                @if ($reply->fileAttachments->count() > 0)
                                <div class="ticket__attachments d-inline-flex gap-1" data-bs-toggle="modal"
                                    data-bs-target="#replyTicketFilesModalForm{{ $reply->id }}">
                                    <i class="fa-solid fa-file-image"></i>
                                    <small class="attachment__count">{{ $reply->fileAttachments->count() }}</small>
                                    <small class="attachment__label">Attachments</small>
                                </div>
                                @endif
                                <i class="fa-solid fa-circle-check ticket__reply__content__check__icon"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="alert alert-warning py-3 px-3 rounded-3" style="margin: 20px 0px;">
                        <small style="font-size: 14px;">No replies.</small>
                    </div>
                    @endif
                    <button type="button"
                        class="btn btn__reply__ticket btn__reply__ticket__mobile mb-4 mt-5 d-flex align-items-center justify-content-center gap-2"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasReplyTicketForm"
                        aria-controls="offcanvasBottom">
                        <i class="fa-solid fa-pen"></i>
                        <span class="lbl__reply">Reply</span>
                    </button>
                    {{-- End Replies/Comments --}}
                </div>
                <div class="col-md-4">
                    <div class="container__ticket__details__right">
                        <div class="card border-0 p-0 card__ticket__details">
                            <div class="ticket__details__card__body__right">
                                <div class="mb-3">
                                    <label class="ticket__actions__label">Ticket details</label>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label" style="font-weight: 500;">
                                            Approval status:
                                        </small>
                                        <small class="ticket__details__info">
                                            @if ($ticket->approval_status === 'approved')
                                            <i class="fa-solid fa-circle-check" style="color: #D32839;"></i>
                                            Approved
                                            @elseif ($ticket->approval_status === 'for_approval')
                                            <i class="fa-solid fa-paper-plane" style="color: #D32839;"></i>
                                            For Approval
                                            @elseif ($ticket->approval_status === 'disapproved')
                                            <i class="fa-solid fa-xmark" style="color: #D32839;"></i>
                                            Disapproved
                                            @else
                                            ----
                                            @endif
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label"
                                            style="font-weight: 500;">Branch:</small>
                                        <small class="ticket__details__info">{{ $ticket->branch->name }}</small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label" style="font-weight: 500;">
                                            Service department:</small>
                                        <small
                                            class="ticket__details__info">{{ $ticket->serviceDepartment->name }}</small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label"
                                            style="font-weight: 500;">Team:</small>
                                        <small class="ticket__details__info">{{ $ticket->team->name }}</small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label" style="font-weight: 500;">
                                            Help topic:
                                        </small>
                                        <small class="ticket__details__info">{{ $ticket->helpTopic->name }}</small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label" style="font-weight: 500;">
                                            Assigned agent:
                                        </small>
                                        <small class="ticket__details__info {{ $ticket->agent ? '' : 'not__set'}}">
                                            {{ $ticket->agent->name ?? '----' }}
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="ticket__details__info__label" style="font-weight: 500;">
                                            SLA:</small>
                                        <small
                                            class="ticket__details__info">{{ $ticket->sla->time_unit ?? '----' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                        <li>
                                            <small>
                                                Transfer this ticket to other department.
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @include('layouts.staff.ticket.modal.ticket_actions_modal')
@include('layouts.staff.ticket.modal.reply_ticket_modal')--}}
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
