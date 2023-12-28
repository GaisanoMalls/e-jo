<div wire:init="loadClarifications">
    @if (!is_null($clarifications))
        <div wire:poll.visible.7s>
            @if ($ticket->clarifications->isNotEmpty())
                @foreach ($ticket->clarifications as $clarification)
                    <div class="card border-0 p-0 card__ticket__details"
                        style="width: fit-content; max-width: 70%;
                            {{ $clarification->user_id == auth()->user()->id ? 'background-color: #D0F0F7; margin-left: auto;' : 'background-color: #E9ECEF; margin-right: auto;' }}">
                        <div
                            class="ticket__details__card__header d-flex pb-0 align-items-center justify-content-between">
                            <div class="d-flex align-items-center w-100">
                                @if ($clarification->user->id !== auth()->user()->id)
                                    @if ($clarification->user->profile->picture)
                                        <img src="{{ Storage::url($clarification->user->profile->picture) }}"
                                            alt=""
                                            class="image-fluid ticket__details__user__picture
                                            reply__ticket__details__user__picture">
                                    @else
                                        <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                        text-white"
                                            style="background-color: #24695C;">
                                            {{ $clarification->user->profile->getNameInitial() }}</div>
                                    @endif
                                @endif
                                <div class="d-flex flex-wrap justify-content-between w-100">
                                    @if ($clarification->user->id !== auth()->user()->id)
                                        <small
                                            class="pe-3 ticket__details__user__fullname reply__ticket__details__user__fullname">
                                            {{ $clarification->user->profile->getFullName() }}
                                            @if ($clarification->user->hasRole(App\Models\Role::SYSTEM_ADMIN))
                                                <i class="bi bi-person-fill-gear text-muted ms-1"
                                                    title="System Admin"></i>
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
                                <div class="ticket__description reply__ticket__description">
                                    {!! $clarification->description !!}
                                </div>
                                @if ($clarification->fileAttachments->count() > 0)
                                    <div class="ticket__attachments d-inline-flex gap-1" data-bs-toggle="modal"
                                        data-bs-target="#clarificationFilesModalForm{{ $clarification->id }}">
                                        <i class="fa-solid fa-file-image"></i>
                                        <small
                                            class="attachment__count">{{ $clarification->fileAttachments->count() }}</small>
                                        <small class="attachment__label">Attachments</small>
                                    </div>
                                @endif
                                <i class="fa-solid fa-circle-check ticket__reply__content__check__icon"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Modal to preview file attached in the reply. --}}
                    <div wire:ignore.self class="modal fade ticket__actions__modal"
                        id="clarificationFilesModalForm{{ $clarification->id }}" tabindex="-1"
                        aria-labelledby="modalFormLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered custom__modal">
                            <div class="modal-content custom__modal__content">
                                <div class="modal__header d-flex justify-content-between align-items-center">
                                    <h6 class="modal__title">
                                        {{ $clarification->fileAttachments->count() > 1 ? 'Reply file attachments' : 'Reply file attachment' }}
                                        ({{ $clarification->fileAttachments->count() }})
                                    </h6>
                                </div>
                                <div class="modal__body mt-3">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($clarification->fileAttachments as $clarificationFile)
                                            <li
                                                class="list-group-item d-flex align-items-center px-0 py-3 justify-content-between">
                                                <a href="{{ Storage::url($clarificationFile->file_attachment) }}"
                                                    target="_blank">
                                                    <div class="d-flex align-items-center gap-2">
                                                        @switch(pathinfo(basename($clarificationFile->file_attachment),
                                                            PATHINFO_EXTENSION))
                                                            @case('jpeg')
                                                                <img src="{{ Storage::url($clarificationFile->file_attachment) }}"
                                                                    class="file__preview">
                                                            @break

                                                            @case('jpg')
                                                                <img src="{{ Storage::url($clarificationFile->file_attachment) }}"
                                                                    class="file__preview">
                                                            @break

                                                            @case('png')
                                                                <img src="{{ Storage::url($clarificationFile->file_attachment) }}"
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
                                                            {{ basename($clarificationFile->file_attachment) }}</p>
                                                    </div>
                                                </a>
                                                <a href="{{ Storage::url($clarificationFile->file_attachment) }}"
                                                    download target="_blank" style="font-size: 20px;">
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
