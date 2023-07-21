@if ($ticket->clarifications->count() > 0)
@foreach ($ticket->clarifications as $clarification)
@include('layouts.staff.approver.ticket.includes.modal.preview_clarification_ticket_files_modal')
<div class="card border-0 p-0 shadow-none card__ticket__details"
    style="width: fit-content; max-width: 70%;
    {{ $clarification->user_id === auth()->user()->id ? 'background-color: #dff9ff; margin-left: auto;' : 'background-color: #F5F7F9; margin-right: auto;' }}">
    <div class="ticket__details__card__header d-flex pb-0 align-items-center justify-content-between">
        <div class="d-flex align-items-center w-100">
            @if ($clarification->user->role_id === App\Models\Role::USER)
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
                @if ($clarification->user->role_id === App\Models\Role::USER)
                <small class="pe-3 ticket__details__user__fullname reply__ticket__details__user__fullname">
                    {{ $clarification->user->profile->getFullName() }}
                </small>
                @else
                <small class="pe-3 text-muted" style="font-size: 12px;">Sent</small>
                @endif
                <small class="ticket__details__time">{{ $clarification->created_at->diffForHumans(null, true) }}</small>
            </div>
        </div>
    </div>
    <div class="ticket__details__card__body pb-3">
        <div class="ticket__reply__content">
            <div class="ticket__description reply__ticket__description">{!! $clarification->description !!}
            </div>
            @if ($clarification->fileAttachments->count() > 0)
            <div class="ticket__attachments d-inline-flex gap-1" data-bs-toggle="modal"
                data-bs-target="#clarificationTicketFilesModalForm{{ $clarification->id }}">
                <i class="fa-solid fa-file-image"></i>
                <small class="attachment__count">{{ $clarification->fileAttachments->count() }}</small>
                <small class="attachment__label">{{ $clarification->fileAttachments->count() > 1 ? 'Attachments' :
                    'Attachment' }} </small>
            </div>
            @endif
            <i class="fa-solid fa-circle-check ticket__reply__content__check__icon"></i>
        </div>
    </div>
</div>
@endforeach
@endif