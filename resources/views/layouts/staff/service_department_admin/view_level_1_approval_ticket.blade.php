@extends('layouts.staff.base', ['title' => $ticket->subject])

@section('main-content')
@include('layouts.staff.service_department_admin.includes.modal.disapproval_reason')
@if ($reason)
@include('layouts.staff.service_department_admin.includes.modal.reason')
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
<div class="ticket__section">
    <div class="row">
        <div class="col-xl-12 ticket__details__container">
            <div class="mb-3 ticket__details__top">
                <a href="{{ route('staff.service_dept_head.level_1_approval.index') }}" type="button"
                    class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div class="d-flex align-items-center gap-3 mb-4">
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
                            <form action="{{ route('staff.ticket.close_ticket', $ticket->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex
                                    align-items-center justify-content-center" type="submit">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </button>
                            </form>
                            <small class="ticket__details__topbuttons__label">Approve</small>
                        </div>
                        <div class="d-flex flex-column">
                            <form action="{{ route('staff.ticket.close_ticket', $ticket->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex
                                    align-items-center justify-content-center" type="submit">
                                    <i class="fa-solid fa-thumbs-down"></i>
                                </button>
                            </form>
                            <small class="ticket__details__topbuttons__label">Disapprove</small>
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
                            <small class="ticket__details__time mt-2">
                                {{ $ticket->created_at->diffForHumans(null, true) }} ago
                            </small>
                        </div>
                        <div class="ticket__details__card__body">
                            <div class="ticket__description">{!! $ticket->description !!}</div>
                            @if ($ticket->fileAttachments->count() > 0)
                            <div class="ticket__attachments d-inline-flex gap-1 mb-3" data-bs-toggle="modal"
                                data-bs-target="#previewTicketFileModal">
                                <i class="fa-solid fa-file-zipper"></i>
                                <small class="attachment__count">{{ $ticket->fileAttachments->count() }}</small>
                                <small class="attachment__label">
                                    {{ $ticket->fileAttachments->count() > 1
                                    ? 'file attachments'
                                    : 'file attachement'
                                    }}
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mb-2 mt-4">
                        <small class="ticket__discussions text-muted">
                            {{ $ticket->clarifications->count() > 1 ? 'Clarifications' : 'Clarification' }}
                            ({{ $ticket->clarifications->count() }})
                        </small>
                    </div>
                    {{-- Replies/Comments --}}
                    @if ($ticket->clarifications->count() > 0)
                    @foreach ($ticket->clarifications as $clarification)
                    @include('layouts.staff.service_department_admin.includes.modal.preview_clarification_ticket_files_modal')
                    <div class="card border-0 p-0 card__ticket__details"
                        style="width: fit-content; max-width: 70%;
                        {{ $clarification->user_id == auth()->user()->id ? 'background-color: #D0F0F7; margin-left: auto;' : 'background-color: #E9ECEF; margin-right: auto;' }}">
                        <div
                            class="ticket__details__card__header d-flex pb-0 align-items-center justify-content-between">
                            <div class="d-flex align-items-center w-100">
                                @if ($clarification->user->id !== auth()->user()->id)
                                @if ($clarification->user->profile->picture)
                                <img src="{{ Storage::url($clarification->user->profile->picture) }}" alt="" class="image-fluid ticket__details__user__picture
                                        reply__ticket__details__user__picture">
                                @else
                                <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                    text-white" style="background-color: #24695C;">
                                    {{ $clarification->user->profile->getNameInitial() }}</div>
                                @endif
                                @endif
                                <div class="d-flex flex-wrap justify-content-between w-100">
                                    @if ($clarification->user->id !== auth()->user()->id)
                                    <small
                                        class="pe-3 ticket__details__user__fullname reply__ticket__details__user__fullname">
                                        {{ $clarification->user->profile->getFullName() }}
                                        @if ($clarification->user->role_id == App\Models\Role::SYSTEM_ADMIN)
                                        <i class="bi bi-person-fill-gear text-muted ms-1" title="System Admin"></i>
                                        @endif
                                    </small>
                                    @else
                                    <small class="pe-3 text-muted" style="font-size: 12px;">Sent</small>
                                    @endif
                                    <small class="ticket__details__time">
                                        {{ $clarification->created_at->diffForHumans(null, true) }} ago
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="ticket__details__card__body pb-3">
                            <div class="ticket__reply__content">
                                <div class="ticket__description reply__ticket__description">{!!
                                    $clarification->description !!}
                                </div>
                                @if ($clarification->fileAttachments->count() > 0)
                                <div class="ticket__attachments d-inline-flex gap-1" data-bs-toggle="modal"
                                    data-bs-target="#clarificationTicketFilesModalForm{{ $clarification->id }}">
                                    <i class="fa-solid fa-file-image"></i>
                                    <small class="attachment__count">{{ $clarification->fileAttachments->count()
                                        }}</small>
                                    <small class="attachment__label">Attachments</small>
                                </div>
                                @endif
                                <i class="fa-solid fa-circle-check ticket__reply__content__check__icon"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                    @if ($ticket->clarifications->count() === 0)
                    <div class="alert alert-warning py-4 px-3 rounded-3 d-flex flex-wrap gap-3 align-items-center justify-content-between"
                        style="margin: 20px 0px;">
                        <small style="font-size: 14px;">Connect to requester if you have any questions or clarifications
                            with regards to this ticket.</small>
                        <button type="button"
                            class="btn btn__reply__ticket btn__reply__ticket__mobile d-flex align-items-center justify-content-center gap-2"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasClarificationModal"
                            aria-controls="offcanvasBottom">
                            <i class="fa-solid fa-pen"></i>
                            <span class="lbl__reply">Connect with {{ $ticket->user->profile->first_name }}</span>
                        </button>
                    </div>
                    @else
                    <button type="button"
                        class="btn btn__reply__ticket btn__reply__ticket__mobile d-flex align-items-center justify-content-center gap-2"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasClarificationModal"
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
                                            {{ $ticket->team->name }}
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
                                            @if ($ticket->agent)
                                            {{ $ticket->agent->profile->getFullName() }}
                                            @else
                                            ----
                                            @endif
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
</div>
@include('layouts.staff.service_department_admin.includes.modal.preview_ticket_files_modal')
@include('layouts.staff.service_department_admin.includes.offcanvas.ticket_clarifications_offcanvas')
@endsection