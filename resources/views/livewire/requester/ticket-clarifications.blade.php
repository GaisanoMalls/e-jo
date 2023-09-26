<div wire:init="loadClarifications">
    @if (!is_null($clarifications))
    @if ($ticket->clarifications->count() === 0 && $ticket->status_id !== App\Models\Status::CLOSED)
    <div class="row align-items-center px-2 py-3 rounded-3 mx-1 mt-2 mb-4" style="background-color: #FFF3CD;">
        <div class="col-lg-6 col-md-12">
            <p class="mb-0" style="font-size: 13px; line-height: 18px;">
                Connect to approver if you have any questions or clarifications with regards to this ticket.
            </p>
        </div>
        <div class="col-lg-6 col-md-12">
            <div
                class="d-flex align-items-center justify-content-start justify-content-lg-end justify-content-md-start">
                <button type="button"
                    class="btn btn__reply__ticket btn__reply__ticket__mobile mb-4 mt-4 d-flex align-items-center justify-content-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#ticketClarificationModal">
                    <i class="fa-solid fa-pen"></i>
                    <span class="lbl__reply">Connect with approver</span>
                </button>
            </div>
        </div>
    </div>
    @endif
    <div wire:poll.visible.7s>
        @if (!$ticket->clarifications->isEmpty())
        @foreach ($ticket->clarifications as $clarification)
        @include('layouts.user.ticket.includes.modal.preview_clarification_ticket_files_modal')
        <div class="card border-0 p-0 card__ticket__details"
            style="width: fit-content; max-width: 70%;
            {{ $clarification->user_id === auth()->user()->id ? 'background-color: #D0F0F7; margin-left: auto;' : 'background-color: #E9ECEF; margin-right: auto;' }}">
            <div class="ticket__details__card__header d-flex pb-0 align-items-center justify-content-between">
                <div class="d-flex align-items-center w-100">
                    @if ($clarification->user->role_id !== App\Models\Role::USER)
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
                        @if ($clarification->user->role_id !== App\Models\Role::USER)
                        <small class="pe-3 ticket__details__user__fullname reply__ticket__details__user__fullname">
                            {{ $clarification->user->profile->getFullName() }}
                            {{ $clarification->user_id === auth()->user()->id ? '(me)' : '' }}
                        </small>
                        @else
                        <small class="pe-3 text-muted" style="font-size: 12px;">Sent</small>
                        @endif
                        <small class="ticket__details__time">
                            {{ $clarification->created_at->diffForHumans(null, true) }}
                            ago
                        </small>
                    </div>
                </div>
            </div>
            <div class="ticket__details__card__body pb-3">
                <div class="ticket__reply__content">
                    <div class="ticket__description reply__ticket__description">{!! $clarification->description !!}
                    </div>
                    @if (!$clarification->fileAttachments->isEmpty())
                    <div class="ticket__attachments d-inline-flex gap-1" data-bs-toggle="modal"
                        data-bs-target="#replyTicketFilesModalForm{{ $clarification->id }}">
                        <i class="fa-solid fa-file-image"></i>
                        <small class="attachment__count">{{ $clarification->fileAttachments->count() }}</small>
                        <small class="attachment__label">
                            {{ $clarification->fileAttachments->count() > 1
                            ? 'file attachments'
                            : 'file attachment'
                            }}
                        </small>
                    </div>
                    @endif
                    <i class="fa-solid fa-circle-check ticket__reply__content__check__icon"></i>
                </div>
            </div>
        </div>
        @endforeach
        @if ($ticket->clarifications->count() !== 0 && $ticket->status_id !== App\Models\Status::CLOSED)
        <button type="button" class="btn btn__reply__ticket btn__reply__ticket__mobile mb-4 mt-5 d-flex align-items-center
            justify-content-center gap-2 float-end" data-bs-toggle="modal" data-bs-target="#ticketClarificationModal"
            wire:click="getLatestClarification">
            <i class="fa-solid fa-pen"></i>
            <span class="lbl__reply">Reply</span>
        </button>
        @endif
        @else
        <div class="alert alert-warning py-3 px-3 rounded-3" style="margin: 20px 0px;">
            <small style="font-size: 14px;">No ticket clarifications</small>
        </div>
        @endif
    </div>
    @else
    <div class="d-flex justify-content-center">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    @endif
</div>