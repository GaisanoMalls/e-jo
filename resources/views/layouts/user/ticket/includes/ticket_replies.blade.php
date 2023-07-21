@if ($ticket->replies->count() > 0)
@foreach ($ticket->replies as $reply)
@include('layouts.user.ticket.includes.modal.preview_reply_ticket_files_modal')
<div class="card border-0 p-0 card__ticket__details"
    style="width: fit-content; max-width: 70%;
    {{ $reply->user_id === auth()->user()->id ? 'background-color: #D0F0F7; margin-left: auto;' : 'background-color: #E9ECEF; margin-right: auto;' }}">
    <div class="ticket__details__card__header d-flex pb-0 align-items-center justify-content-between">
        <div class="d-flex align-items-center w-100">
            @if ($reply->user->role_id !== App\Models\Role::USER)
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
                @if ($reply->user->role_id !== App\Models\Role::USER)
                <small class="pe-3 ticket__details__user__fullname reply__ticket__details__user__fullname">
                    {{ $reply->user->profile->getFullName() }}
                    @if ($reply->user->role_id === App\Models\Role::SYSTEM_ADMIN)
                    <i class="bi bi-person-fill-gear text-muted ms-1" title="System Admin"></i>
                    @endif
                </small>
                @else
                <small class="pe-3 text-muted" style="font-size: 12px;">Sent</small>
                @endif
                <small class="ticket__details__time">{{ $reply->created_at->diffForHumans(null, true) }}</small>
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
@if ($ticket->status_id === App\Models\Status::ON_PROCESS)
<button type="button" class="btn btn__reply__ticket btn__reply__ticket__mobile mb-4 mt-5 d-flex align-items-center
                    justify-content-center gap-2" data-bs-toggle="offcanvas"
    data-bs-target="#offcanvasRequesterReplyTicketForm" aria-controls="offcanvasBottom">
    <i class="fa-solid fa-pen"></i>
    <span class="lbl__reply">Reply</span>
</button>
@endif
@else
<div class="alert alert-warning py-3 px-3 rounded-3" style="margin: 20px 0px;">
    <small style="font-size: 14px;">No replies.</small>
</div>
@endif