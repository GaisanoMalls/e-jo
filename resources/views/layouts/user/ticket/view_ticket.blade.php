@extends('layouts.user.base', ['title' => $ticket->subject ])

@section('main-content')
<div class="row mx-0">
    <div class="card ticket__card" id="userTicketCard">
        <div class="ticket__details__section">
            <div class="details__card__top mb-3 d-flex flex-column">
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
                                <img src="{{ asset('storage/' . $ticket->user->profile->picture) }}"
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
                    @if ($ticket->replies->count() > 0)
                    @foreach ($ticket->replies as $reply)
                    @include('layouts.user.ticket.includes.modal.preview_reply_ticket_files_modal')
                    <div class="card border-0 p-0 card__ticket__details"
                        style="background-color: {{ $reply->user_id === auth()->user()->id ? '#f3f3f3' : '' }}">
                        <div
                            class="ticket__details__card__header d-flex pb-0 align-items-center justify-content-between">
                            <div class="d-flex align-items-center w-100">
                                @if ($reply->user->profile->picture)
                                <img src="{{ Storage::url($reply->user->profile->picture) }}" alt="" class="image-fluid ticket__details__user__picture
                                        reply__ticket__details__user__picture">
                                @else
                                <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                    text-white" style="background-color: #24695C;">
                                    {{ $reply->user->profile->getNameInitial() }}</div>
                                @endif
                                <div class="d-flex flex-wrap justify-content-between w-100">
                                    <small
                                        class="ticket__details__user__fullname reply__ticket__details__user__fullname">
                                        {{ $reply->user->profile->getFullName() }}
                                        {{ $reply->user_id === auth()->user()->id ? '(me)' : '' }}
                                    </small>
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
                                            SLA:</small>
                                        <small
                                            class="ticket__details__info">{{ $ticket->sla->time_unit ?? '----' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 p-0 card__ticket__details card__ticket__details__right">
                            <div class="ticket__details__card__body__right">
                                <div class="mb-3 d-flex justify-content-between">
                                    <small class="ticket__actions__label">Assigned Agent</small>
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
