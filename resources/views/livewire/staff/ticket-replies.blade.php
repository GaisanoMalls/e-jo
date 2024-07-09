@php
    use App\Models\Role;
@endphp

<div wire:init="loadReplies">
    @if (!is_null($replies))
        <div wire:poll.visible>
            @if ($ticket->replies->isNotEmpty())
                @foreach ($ticket->replies as $reply)
                    <div id="reply-{{ $reply->id }}"
                        class="d-flex mb-3 flex-row align-items-center gap-1 reply__container {{ $reply->user_id == auth()->user()->id ? 'flex-row-reverse' : '' }}">
                        <div class="d-flex flex-column qoute__reply__action__container rounded-5 shadow"
                            style="{{ $reply->user_id == auth()->user()->id ? 'right: -10px; ' : 'left: -10px;' }}">
                            @if ($this->isLiked($reply))
                                <button
                                    class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle btn__reply__qoute__action"
                                    wire:click="unlikeReply({{ $reply->id }})">
                                    <i class="fa-solid fa-thumbs-up" style="color: #c5b236;"></i>
                                </button>
                            @else
                                <button
                                    class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle btn__reply__qoute__action"
                                    wire:click="likeReply({{ $reply->id }})">
                                    <i class="fa-regular fa-thumbs-up"></i>
                                </button>
                            @endif
                            <button
                                class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle btn__reply__qoute__action btn__qoute__reply"
                                data-bs-toggle="modal" data-bs-target="#qouteReplyModal"
                                wire:click="qouteReply({{ $reply->id }})">
                                <i class="bi bi-reply-fill"
                                    style="{{ $reply->user_id != auth()->user()->id ? 'transform: scaleX(-1);' : '' }}"></i>
                            </button>
                        </div>
                        <div class="card border-0 p-0 card__ticket__details"
                            style="width: fit-content; max-width: 70%;
                            {{ $reply->user_id == auth()->user()->id ? 'background-color: #D0F0F7; margin-left: auto;' : 'background-color: #E9ECEF; margin-right: auto;' }}">
                            @if ($reply->quotedReply)
                                <div class="text-muted qouted__reply position-relative"
                                    style="font-size: 12px; background-color: #FFFFFF; border-radius: 0.625rem; {{ $reply->quotedReply && $reply->user_id == auth()->user()->id ? 'border: 0.15rem solid #D0F0F7;' : 'border: 0.15rem solid #E9ECEF;' }} ">
                                    <div class="d-flex align-items-center mb-2">
                                        @if ($reply->quotedReply->user->profile->picture)
                                            <img src="{{ Storage::url($reply->quotedReply->user->profile->picture) }}"
                                                alt="" class="image-fluid reply__qoute__user__picture me-2">
                                        @else
                                            <div class="reply__qoute__user__name__initial d-flex align-items-center me-2 justify-content-center text-white"
                                                style="background-color: #24695C;">
                                                {{ $reply->quotedReply->user->profile->getNameInitial() }}</div>
                                        @endif
                                        <small
                                            style="font-size: 0.7rem;">{{ $reply->quotedReply->user->profile->getFullName() }}</small>
                                    </div>
                                    {!! Str::limit($reply->quotedReply?->description, 200, '...') !!}
                                    <a href="#reply-{{ $reply->quotedReply->id }}" class="position-absolute"
                                        style="right: 4px; top: 4px;">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            @endif
                            <div
                                class="ticket__details__card__header d-flex pb-0 align-items-center justify-content-between">
                                <div class="d-flex align-items-center w-100">
                                    @if ($reply->user->id !== auth()->user()->id)
                                        @if ($reply->user->profile->picture)
                                            <img src="{{ Storage::url($reply->user->profile->picture) }}"
                                                alt=""
                                                class="image-fluid ticket__details__user__picture
                                            reply__ticket__details__user__picture">
                                        @else
                                            <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center text-white"
                                                style="background-color: #24695C;">
                                                {{ $reply->user->profile->getNameInitial() }}</div>
                                        @endif
                                    @endif
                                    <div class="d-flex flex-wrap justify-content-between w-100">
                                        @if ($reply->user->id !== auth()->user()->id)
                                            <small
                                                class="pe-3 ticket__details__user__fullname reply__ticket__details__user__fullname">
                                                {{ $reply->user->profile->getFullName() }}
                                                @if ($reply->user->hasRole(Role::SYSTEM_ADMIN))
                                                    <i class="bi bi-person-fill-gear text-muted ms-1"
                                                        title="System Admin"></i>
                                                @endif
                                            </small>
                                        @else
                                            <small class="pe-3 text-muted" style="font-size: 12px;">Sent</small>
                                        @endif
                                        <small class="ticket__details__time">
                                            {{ $reply->created_at->diffForHumans(null, true) }} ago
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="ticket__details__card__body pb-3">
                                <div class="ticket__reply__content">
                                    <div class="ticket__description reply__ticket__description">
                                        {!! $reply->description !!}
                                    </div>
                                    @if ($reply->fileAttachments->count() > 0)
                                        <div class="ticket__attachments d-inline-flex mt-3 gap-1" data-bs-toggle="modal"
                                            data-bs-target="#replyTicketFilesModalForm{{ $reply->id }}">
                                            <i class="fa-solid fa-file-image"></i>
                                            <small
                                                class="attachment__count">{{ $reply->fileAttachments->count() }}</small>
                                            <small class="attachment__label">Attachments</small>
                                        </div>
                                    @endif
                                    <i class="fa-solid fa-circle-check ticket__reply__content__check__icon"></i>
                                </div>
                            </div>
                        </div>
                        @if ($reply->likes->isNotEmpty())
                            <div class="d-flex flex-column likes__container rounded-5 shadow-sm"
                                style="{{ $reply->user_id == auth()->user()->id ? 'right: -10px; ' : 'left: 12px;' }}">
                                <button
                                    class="btn btn-sm d-flex align-items-center gap-1 justify-content-center rounded-circle btn__reply__qoute__action btn__display__reply__likes"
                                    style="{{ $reply->likes->count() > 1 ? 'width: auto !important;' : '' }}">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                    {{ $reply->likes->count() > 1 ? $reply->likes->count() : '' }}
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Modal to preview file attached in the reply. --}}
                    <div wire:ignore.self class="modal fade ticket__actions__modal"
                        id="replyTicketFilesModalForm{{ $reply->id }}" tabindex="-1"
                        aria-labelledby="modalFormLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered custom__modal">
                            <div class="modal-content custom__modal__content">
                                <div class="modal__header d-flex justify-content-between align-items-center">
                                    <h6 class="modal__title">
                                        {{ $reply->fileAttachments->count() > 1 ? 'Reply file attachments' : 'Reply file attachment' }}
                                        ({{ $reply->fileAttachments->count() }})
                                    </h6>
                                </div>
                                <div class="modal__body mt-3">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($reply->fileAttachments as $replyFile)
                                            <li
                                                class="list-group-item d-flex align-items-center px-0 py-3 justify-content-between">
                                                <a href="{{ Storage::url($replyFile->file_attachment) }}"
                                                    target="_blank">
                                                    <div class="d-flex align-items-center gap-2">
                                                        @switch(pathinfo(basename($replyFile->file_attachment),
                                                            PATHINFO_EXTENSION))
                                                            @case('jpeg')
                                                                <img src="{{ Storage::url($replyFile->file_attachment) }}"
                                                                    class="file__preview">
                                                            @break

                                                            @case('jpg')
                                                                <img src="{{ Storage::url($replyFile->file_attachment) }}"
                                                                    class="file__preview">
                                                            @break

                                                            @case('png')
                                                                <img src="{{ Storage::url($replyFile->file_attachment) }}"
                                                                    class="file__preview">
                                                            @break

                                                            @case('pdf')
                                                                <i class="bi bi-filetype-pdf" style="font-size: 35px;"></i>
                                                            @break

                                                            @case('doc')
                                                                <i class="bi bi-filetype-doc" style="font-size: 35px;"></i>
                                                            @break

                                                            @case('docx')
                                                                <i class="bi bi-filetype-docx" style="font-size: 35px;"></i>
                                                            @break

                                                            @case('xlsx')
                                                                <i class="bi bi-filetype-xlsx" style="font-size: 35px;"></i>
                                                            @break

                                                            @case('xls')
                                                                <i class="bi bi-filetype-xls" style="font-size: 35px;"></i>
                                                            @break

                                                            @case('csv')
                                                                <i class="bi bi-filetype-csv" style="font-size: 35px;"></i>
                                                            @break

                                                            @default
                                                        @endswitch
                                                        <p class="mb-0" style="font-size: 14px;">
                                                            {{ basename($replyFile->file_attachment) }}</p>
                                                    </div>
                                                </a>
                                                <a href="{{ Storage::url($replyFile->file_attachment) }}" download
                                                    target="_blank" style="font-size: 20px;">
                                                    <i class="fa-solid fa-download"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div wire:ignore.self class="modal fade modal-xl ticket__actions__modal" id="qouteReplyModal"
                    tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered custom__modal">
                        <div class="modal-content d-flex flex-column custom__modal__content">
                            <div class="modal__header d-flex justify-content-between align-items-center">
                                <h6 class="modal__title">Write your reply</h6>
                                <button
                                    class="btn d-flex align-items-center justify-content-center modal__close__button"
                                    data-bs-dismiss="modal" id="btnCloseModal">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div class="my-4 d-flex flex-column reply__ticket__info">
                                <div class="mb-0 ticket__description">{!! $qouteReplyMessage !!}</div>
                            </div>
                            <div class="modal__body">
                                <form wire:submit.prevent="sendQuoteReply">
                                    <div class="my-2">
                                        <div wire:ignore>
                                            <textarea wire:model="quoteReplyDescription" id="quoteReplyDescription"></textarea>
                                        </div>
                                        @error('quoteReplyDescription')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mt-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <label class="ticket__actions__label">Attach file</label>
                                            <div wire:loading wire:target="quoteReplyFiles">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="spinner-border text-info"
                                                        style="height: 15px; width: 15px;" role="status">
                                                    </div>
                                                    <small style="fot-size: 12px;">Uploading...</small>
                                                </div>
                                            </div>
                                        </div>
                                        <input class="form-control ticket__file__input w-auto my-3" type="file"
                                            wire:model="quoteReplyFiles" multiple id="upload-{{ $upload }}">
                                        @error('quoteReplyFiles.*')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <button type="submit"
                                        class="btn mt-4 d-flex align-items-center justify-content-center gap-2 modal__footer__button modal__btnsubmit__bottom">
                                        <span wire:loading wire:target="sendQuoteReply"
                                            class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true">
                                        </span>
                                        Send
                                        <div wire:loading.remove wire:target="sendQuoteReply">
                                            <i class="bi bi-send-fill"></i>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning py-3 px-3 rounded-3" style="margin: 20px 0px;">
                    <small style="font-size: 14px;">No replies.</small>
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

@push('qoutereply-textarea')
    <script>
        tinymce.init({
            selector: '#quoteReplyDescription',
            plugins: 'lists',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist',
            height: 350,
            forced_root_block: false,
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
                editor.on('change', function() {
                    @this.set('quoteReplyDescription', editor.getContent());
                });
            }
        });
    </script>
@endpush

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#qouteReplyModal').modal('hide');
            tinymce.get("quoteReplyDescription").setContent("");
        });
    </script>
@endpush
