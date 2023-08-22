@extends('layouts.staff.approver.base', ['title' => $ticket->subject])

@section('main-content')
@include('layouts.staff.approver.ticket.includes.modal.disapproval_reason')
@if ($reason)
@include('layouts.staff.approver.ticket.includes.modal.reason')
@endif
@if ($ticket->approval_status === App\Models\ApprovalStatus::DISAPPROVED)
<div class="alert alert-warning p-3 rounded-3 mx-1 mt-2 mb-4 d-flex align-items-center justify-content-between"
    role="alert" style="font-size: 13px;">
    <span style="font-size: 13px;">
        This ticket has been disapproved.
    </span>
    @if ($reason)
    <button type="button" class="btn btn-sm p-0 d-flex align-items-center border-0 rounded-0 gap-1 btn__see__reason"
        data-bs-toggle="modal" data-bs-target="#reasonModal">
        See reason
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
                <a href="{{ route('approver.tickets.open') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @case(App\Models\Status::VIEWED)
                <a href="{{ route('approver.tickets.viewed') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @case(App\Models\Status::APPROVED)
                <a href="{{ route('approver.tickets.approved') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @case(App\Models\Status::ON_PROCESS)
                <a href="{{ route('approver.tickets.on_process') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                @break
                @endswitch
                @if ($ticket->approval_status === App\Models\ApprovalStatus::DISAPPROVED)
                <a href="{{ route('approver.tickets.disapproved') }}" type="button"
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
                <div class="d-flex flex-wrap align-items-center justify-content-between ticket__details__header">
                    <div class="mb-2">
                        <h6 class="ticket__details__title mb-0">{{ $ticket->subject }}</h6>
                        <small class="ticket__details__datetime">{{ $ticket->dateCreated() }},
                            {{ $ticket->created_at->format('D') }} @ {{ $ticket->created_at->format('g:i A') }}</small>
                    </div>
                    @if ($ticket->status_id === App\Models\Status::OPEN || $ticket->approval_status ===
                    App\Models\ApprovalStatus::FOR_APPROVAL)
                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
                        <button type="button" class="btn btn-sm btn__disapprove__ticket" data-bs-toggle="modal"
                            data-bs-target="#disapproveTicketModal" type="button">
                            Disapprove
                        </button>
                        <form action="{{ route('approver.ticket.approve_ticket', $ticket->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm shadow btn__approve__ticket">
                                Approve
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 position-relative mb-3">
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
                            </small>
                        </div>
                        <div class="ticket__details__card__body">
                            <div class="ticket__description">{!! $ticket->description !!}</div>
                            @if (!$ticket->fileAttachments->isEmpty())
                            <div class="ticket__attachments d-inline-flex gap-1 mb-3" data-bs-toggle="modal"
                                data-bs-target="#requesterTicketFilesModalForm">
                                <i class="fa-solid fa-file-zipper"></i>
                                <small class="attachment__count">{{ $ticket->fileAttachments->count() }}</small>
                                <small class="attachment__label">
                                    {{ $ticket->fileAttachments->count() > 1
                                    ? 'file attachments'
                                    : 'file attachement' }}
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                    @if ($ticket->status_id !== App\Models\Status::CLOSED || $ticket->approval_status !==
                    App\Models\ApprovalStatus::DISAPPROVED && $ticket->clarifications->count() !== 0)
                    <div class="mb-2 mt-4">
                        <small class="ticket__discussions text-muted">
                            {{ $ticket->clarifications->count() > 1 ? 'Discussions' : 'Discussion' }}
                            ({{ $ticket->clarifications->count() }})
                        </small>
                    </div>
                    {{-- Replies/Comments --}}
                    @include('layouts.staff.approver.ticket.includes.ticket_clarifications')
                    @if ($ticket->clarifications->count() === 0)
                    <div class="row align-items-center bg-light p-2 py-1 rounded-3 mx-1 mt-2 mb-4">
                        <div class="col-md-8">
                            <p class="mb-0" style="font-size: 13px; line-height: 19px;">
                                If you have any questions or clarifications with regards to this
                                ticket, you can connect with {{ $ticket->user->profile->first_name }}.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div
                                class="d-flex align-items-center justify-content-start justify-content-lg-end justify-content-md-end">
                                <button type="button" class="btn btn__reply__ticket btn__reply__ticket__mobile mb-4 mt-4 d-flex align-items-center
                                justify-content-center gap-2" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasTicketClarificationForm" aria-controls="offcanvasBottom">
                                    <i class="fa-solid fa-pen"></i>
                                    <span class="lbl__reply">Connect with {{ $ticket->user->profile->first_name
                                        }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @else
                    <button type="button" class="btn btn__reply__ticket btn__reply__ticket__mobile mb-4 mt-4 d-flex align-items-center
                                                    justify-content-center gap-2" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasTicketClarificationForm" aria-controls="offcanvasBottom">
                        <i class="fa-solid fa-pen"></i>
                        <span class="lbl__reply">Reply</span>
                    </button>
                    @endif
                    @else
                    <div class="row align-items-center bg-light p-2 py-3 rounded-3 mx-1 mt-2 mb-4">
                        <div class="col-md-12">
                            <p class="mb-0" style="font-size: 13px; line-height: 19px;">
                                Discussion with agent has been disabled.
                            </p>
                        </div>
                    </div>
                    @endif
                    {{-- End Replies/Comments --}}
                </div>
                <div class="col-md-4">
                    <div class="container__ticket__details__right">
                        @include('layouts.staff.approver.ticket.includes.ticket_details')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.staff.approver.ticket.includes.modal.preview_ticket_files_modal')
@include('layouts.staff.approver.ticket.includes.offcanvas.ticket_clarifications_offcanvas')
@endsection

@if ($errors->storeTicketClarification->any())
@push('offcanvas-error')
<script>
    $(function () {
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasTicketClarificationForm'));
        offcanvas.show();
    });

</script>
@endpush
@endif

@if ($errors->disapproveTicket->any())
@push('modal-with-error')
<script>
    $(function () {
        $('#disapproveTicketModal').modal('show');
    });

</script>
@endpush
@endif