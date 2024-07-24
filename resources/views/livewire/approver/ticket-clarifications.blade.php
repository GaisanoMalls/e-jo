@php
    use App\Models\Role;
@endphp

<div wire:init="loadClarifications">
    @if (!is_null($clarifications))
        <div wire:poll.visible.7s>
            <div class="mb-2 mt-4">
                <small class="ticket__discussions text-muted">
                    {{ $ticket->clarifications->count() > 1 ? 'Discussions' : 'Discussion' }}
                    ({{ $ticket->clarifications->count() }})
                </small>
            </div>
            @if ($ticket->clarifications->count() > 0)
                @foreach ($ticket->clarifications as $clarification)
                    @include('layouts.staff.approver.ticket.includes.modal.preview_clarification_ticket_files_modal')
                    <div class="card border-0 p-0 shadow-none card__ticket__details"
                        style="width: fit-content; max-width: 70%;
                        {{ $clarification->user_id === auth()->user()->id ? 'background-color: #dff9ff; margin-left: auto;' : 'background-color: #F5F7F9; margin-right: auto;' }}">
                        <div
                            class="ticket__details__card__header d-flex pb-0 align-items-center justify-content-between">
                            <div class="d-flex align-items-center w-100">
                                @if ($clarification->user->hasRole(Role::USER))
                                    @if ($clarification->user->profile->picture)
                                        <img src="{{ Storage::url($clarification->user->profile->picture) }}"
                                            alt=""
                                            class="image-fluid ticket__details__user__picture reply__ticket__details__user__picture">
                                    @else
                                        <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center text-white"
                                            style="background-color: #24695C;">
                                            {{ $clarification->user->profile->getNameInitial() }}
                                        </div>
                                    @endif
                                @endif
                                <div class="d-flex flex-wrap justify-content-between w-100">
                                    @if ($clarification->user->hasRole(Role::USER))
                                        <small
                                            class="pe-3 ticket__details__user__fullname reply__ticket__details__user__fullname">
                                            {{ $clarification->user->profile->getFullName }}
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
                                @if ($clarification->fileAttachments->count() > 0)
                                    <div class="ticket__attachments d-inline-flex gap-1" data-bs-toggle="modal"
                                        data-bs-target="#previewClarificaionFileModal{{ $clarification->id }}">
                                        <i class="fa-solid fa-file-image"></i>
                                        <small
                                            class="attachment__count">{{ $clarification->fileAttachments->count() }}</small>
                                        <small class="attachment__label">
                                            {{ $clarification->fileAttachments->count() > 1 ? 'Attachments' : 'Attachment' }}
                                        </small>
                                    </div>
                                @endif
                                <i class="fa-solid fa-circle-check ticket__reply__content__check__icon"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            @if ($ticket->clarifications->count() === 0)
                <div class="row align-items-center bg-light p-2 py-1 rounded-3 mx-1 mt-2 mb-4">
                    <div class="col-lg-6 col-md-12">
                        <p class="mb-0" style="font-size: 13px; line-height: 19px;">
                            If you have any questions or clarifications with regards to this
                            ticket, you can connect with {{ $ticket->user->profile->first_name }}.
                        </p>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div
                            class="d-flex align-items-center justify-content-start justify-content-lg-end justify-content-md-start">
                            <button type="button"
                                class="btn btn__reply__ticket btn__reply__ticket__mobile mb-4 mt-4 d-flex align-items-center justify-content-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#ticketClarificationModal">
                                <i class="fa-solid fa-pen"></i>
                                <span class="lbl__reply">Connect with
                                    {{ $ticket->user->profile->first_name }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <button type="button"
                    class="btn btn__reply__ticket btn__reply__ticket__mobile mb-4 mt-4 d-flex align-items-center justify-content-center gap-2 float-end"
                    wire:click="getLatestClarification" data-bs-toggle="modal"
                    data-bs-target="#ticketClarificationModal">
                    <i class="fa-solid fa-pen"></i>
                    <span class="lbl__reply">Reply</span>
                </button>
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
