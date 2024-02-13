<div>
    @if (!is_null($ticket->ticketCosting))
        <div class="card border-0 p-0 card__ticket__details">
            <div class="row p- gap-2 justify-content-center ticket__costing__container">
                <div class="col-md-8">
                    <div class="d-flex gap-3 flex-wrap justify-content-between">
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label" @style(['color: #d32839 !important;' => $isCostingGreaterOrEqual])>
                                Actual Cost
                            </small>
                            <div class="d-flex align-items-center gap-1">
                                <span class="currency text-muted" @style(['color: #d32839 !important;' => $isCostingGreaterOrEqual])>₱</span>
                                @if ($editingFieldId === $ticket->ticketCosting->id)
                                    <div class="d-flex flex-column gap-1">
                                        <input type="text" wire:model.defer="amount"
                                            class="form-control p-0 rounded-0 border-0 border-2 border-bottom fw-bold form__field ticket__actual__cost"
                                            id="amount" placeholder="{{ $ticket->ticketCosting->amount }}"
                                            @style(['color: #d32839;' => $isCostingGreaterOrEqual])>
                                        @error('amount')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                @else
                                    <span class="amount" @style(['color: #d32839;' => $isCostingGreaterOrEqual])>
                                        {{ $ticket->ticketCosting?->getAmount() }}
                                    </span>
                                @endif
                                <div class="d-flex align-items-center">
                                    @if ($editingFieldId === $ticket->ticketCosting->id)
                                        <button wire:click="updateTicketCostingAmount"
                                            class="btn btn-sm d-flex ms-2 rounded-circle align-items-center justify-content-center btn__update__costing">
                                            <i class="bi bi-check-lg" wire:loading.class="d-none"
                                                wire:target="updateTicketCostingAmount" style="font-size: 18px;"></i>
                                            <div wire:loading wire:target="updateTicketCostingAmount"
                                                class="spinner-border spinner-border-sm loading__spinner"
                                                role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </button>
                                    @endif
                                    @if ($this->isOnlyAgent($ticket->agent_id))
                                        <button wire:click="toggleEditCostingAmount({{ $ticket->ticketCosting->id }})"
                                            class="btn btn-sm d-flex ms-2 rounded-circle align-items-center justify-content-center btn__edit__costing">
                                            @if ($editingFieldId === $ticket->ticketCosting->id)
                                                <i class="bi bi-x-lg" wire:loading.class="d-none"
                                                    wire:target="toggleEditCostingAmount({{ $ticket->ticketCosting->id }})"></i>
                                                <div wire:loading
                                                    wire:target="toggleEditCostingAmount({{ $ticket->ticketCosting->id }})"
                                                    class="spinner-border spinner-border-sm loading__spinner"
                                                    role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            @else
                                                <i class="bi bi-pencil" wire:loading.class="d-none"
                                                    wire:target="toggleEditCostingAmount({{ $ticket->ticketCosting->id }})"></i>
                                                <div wire:loading
                                                    wire:target="toggleEditCostingAmount({{ $ticket->ticketCosting->id }})"
                                                    class="spinner-border spinner-border-sm loading__spinner"
                                                    role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            @endif
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($editingFieldId !== $ticket->ticketCosting->id)
                            <div class="d-flex flex-column justify-content-between gap-2">
                                <small class="text-muted text-sm costing__header__label">Attachment</small>
                                @if ($ticket->ticketCosting->fileAttachments->count() > 0)
                                    <small class="mb-1 mt-2 costing__labels show__costing__attachments"
                                        data-bs-toggle="modal" data-bs-target="#costingPreviewFileAttachmentModal">
                                        <i class="fa-solid fa-file-zipper"></i>
                                        {{ $ticket->ticketCosting->fileAttachments->count() }}
                                        {{ $ticket->ticketCosting->fileAttachments->count() > 1 ? 'files' : 'file' }}
                                        attached
                                    </small>
                                @else
                                    @if (!$this->isOnlyAgent($ticket->agent_id))
                                        <small
                                            class="mt-1 d-flex align-items-baseline justify-content-center costing__labels btn__show__add__costing__file__modal">
                                            No attachments
                                        </small>
                                    @else
                                        <small
                                            class="mt-1 d-flex align-items-baseline justify-content-center costing__labels btn__show__add__costing__file__modal"
                                            data-bs-toggle="modal" data-bs-target="#addCostingFileModal">
                                            <i class="bi bi-plus"></i>
                                            Attach file
                                        </small>
                                    @endif
                                @endif
                            </div>
                            <div class="d-flex flex-column justify-content-between gap-2">
                                <small class="text-muted text-sm costing__header__label">Date created</small>
                                <small class="mb-1 mt-1 costing__labels mt-2">
                                    {{ $ticket->ticketCosting?->dateCreated() }}
                                    <span style="font-size: 11px;">
                                        ({{ $ticket->ticketCosting?->created_at->format('D') }} @
                                        {{ $ticket->ticketCosting?->created_at->format('g:i A') }})
                                    </span>
                                </small>
                            </div>
                            @if ($this->isSpecialProjectCostingApprover(auth()->user()->id, $ticket))
                                <div class="d-flex flex-column justify-content-between gap-2">
                                    <small class="text-muted text-sm costing__header__label">
                                        @if ($this->isCostingApproved())
                                            Status
                                        @else
                                            Action
                                        @endif
                                    </small>
                                    @if ($this->isCostingApproved())
                                        <small
                                            class="d-flex align-items-center justify-content-center gap-1 rounded-4 approved__costing__status">
                                            <i class="fa-solid fa-check"></i>
                                            Approved
                                        </small>
                                    @else
                                        <button wire:click="approveCosting"
                                            class="btn btn-sm d-flex align-items-center justify-content-center gap-1 rounded-2 btn__approve__costing">
                                            <i class="bi bi-check2-circle" wire:loading.class="d-none"
                                                wire:target="approveCosting"></i>
                                            <div wire:loading wire:target="approveCosting"
                                                class="spinner-border spinner-border-sm loading__spinner"
                                                role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            Approve
                                        </button>
                                    @endif
                                </div>

                                <div class="d-flex flex-column justify-content-between gap-2">
                                    <small class="text-muted text-sm costing__header__label">
                                        Approver
                                    </small>
                                    <div class="d-flex align-items-center costing__approver__container">
                                        <small
                                            class="d-flex align-items-center justify-content-center gap-1 rounded-circle costing__approver__initial">
                                            SS
                                        </small>
                                        <small
                                            class="d-flex align-items-center justify-content-center gap-1 rounded-circle costing__approver__initial">
                                            OB
                                        </small>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-md-1 d-flex justify-content-center">
                    <div class="separator"></div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex flex-column justify-content-between gap-2">
                        <small class="text-muted text-sm costing__header__label">Special Project Cost</small>
                        <div class="d-flex gap-1">
                            <span class="currency" style="color: #d32839;">₱</span>
                            <span class="amount" style="color: #d32839;">
                                {{ $ticket->helpTopic->specialProject?->getAmount() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Ticket Costing Files Modal -->
        <div wire:ignore.self class="modal fade ticket__costing__modal" tabindex="-1"
            id="costingPreviewFileAttachmentModal" aria-labelledby="modalFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered custom__modal">
                @if ($ticket->ticketCosting->fileAttachments->count() !== 0)
                    <div class="modal-content custom__modal__content">
                        @if ($this->isOnlyAgent($ticket->agent_id))
                            @if ($ticket->ticketCosting->fileAttachments->count() > 0)
                                <form wire:submit.prevent="saveAdditionalCostingFiles">
                                    <div class="col-12 mt-auto">
                                        <div class="d-flex align-items-center gap-3">
                                            <label for="ticketSubject" class="form-label ticket__costing__label">
                                                Add new attachment
                                            </label>
                                        </div>
                                        <div x-data="{ isUploading: false, progress: 1 }"
                                            x-on:livewire-upload-start="isUploading = true; progress = 1"
                                            x-on:livewire-upload-finish="isUploading = false"
                                            x-on:livewire-upload-error="isUploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            <input
                                                class="form-control form-control-sm border-0 costing__file__attachment"
                                                type="file" accept=".xlsx,.xls,image/*,.doc,.docx,.pdf,.csv"
                                                wire:model="additionalCostingFiles" multiple
                                                id="upload-{{ $uploadFileCostingCount }}"
                                                onchange="validateCotingFile()">
                                            <div x-transition.duration.500ms x-show="isUploading"
                                                class="progress progress-sm mt-1" style="height: 10px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                    role="progressbar" aria-label="Animated striped example"
                                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                                    x-bind:style="`width: ${progress}%; background-color: #7e8da3;`">
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between"
                                                x-transition.duration.500ms>
                                                <span x-show="isUploading" x-text="progress + '%'"
                                                    style="font-size: 12px;">
                                                </span>
                                                <span class="d-flex align-items-center gap-1"
                                                    style="font-size: 12px;">
                                                    <i x-show="isUploading" class='bx bx-loader-circle bx-spin'
                                                        style="font-size: 14px;"></i>
                                                    <span x-show="isUploading">Uploading...</span>
                                                </span>
                                            </div>
                                        </div>
                                        <span class="error__message" id="excludeEXEfileMessage"></span>
                                        @error('additionalCostingFiles.*')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <button type="submit"
                                        class="btn mt-3 d-flex align-items-center justify-content-center gap-2 modal__footer__button modal__btnsubmit__bottom">
                                        <span wire:loading wire:target="saveAdditionalCostingFiles"
                                            class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true">
                                        </span>
                                        Save
                                    </button>
                                </form>
                            @endif
                            <div class="py-3">
                                <hr>
                            </div>
                        @endif
                        <div class="modal__header d-flex justify-content-between align-items-center">
                            <h6 class="modal__title">
                                {{ $ticket->ticketCosting->fileAttachments->count() > 1 ? 'File attachments' : 'File attachment' }}
                                ({{ $ticket->ticketCosting->fileAttachments->count() }})
                            </h6>
                        </div>
                        <div class="modal__body mt-3">
                            <ul class="list-group list-group-flush">
                                @foreach ($ticket->ticketCosting->fileAttachments->sortByDesc('created_at') as $file)
                                    <li
                                        class="list-group-item d-flex align-items-center px-0 py-3 justify-content-between">
                                        <a href="{{ Storage::url($file->file_attachment) }}" target="_blank">
                                            <div class="d-flex align-items-center gap-2">
                                                @switch(pathinfo(basename($file->file_attachment),
                                                    PATHINFO_EXTENSION))
                                                    @case('jpeg')
                                                        <img src="{{ Storage::url($file->file_attachment) }}"
                                                            class="file__preview">
                                                    @break

                                                    @case('jpg')
                                                        <img src="{{ Storage::url($file->file_attachment) }}"
                                                            class="file__preview">
                                                    @break

                                                    @case('png')
                                                        <img src="{{ Storage::url($file->file_attachment) }}"
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
                                                    {{ basename($file->file_attachment) }}
                                                </p>
                                            </div>
                                        </a>
                                        <div
                                            class="d-flex align-items-center gap-4 file__attachment__actions__container">
                                            @if ($this->isOnlyAgent($ticket->agent_id))
                                                <a type="button" class="file__attachment__action__button"
                                                    wire:key="{{ $file->id }}"
                                                    wire:click="deleteCostingAttachent({{ $file->id }})">
                                                    <i class="bi bi-trash" wire:loading.class="d-none"
                                                        wire:target="deleteCostingAttachent({{ $file->id }})"></i>
                                                    <div wire:loading
                                                        wire:target="deleteCostingAttachent({{ $file->id }})"
                                                        class="spinner-border spinner-border-sm loading__spinner"
                                                        role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </a>
                                            @endif
                                            <a type="button" class="file__attachment__action__button"
                                                href="{{ Storage::url($file->file_attachment) }}" download
                                                target="_blank">
                                                <i class="bi bi-cloud-arrow-down"
                                                    style="font-size: 18px !important;"></i>
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Add costing file --}}
        <div wire:ignore.self class="modal fade ticket__costing__modal" id="addCostingFileModal" tabindex="-1"
            aria-labelledby="modalFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered custom__modal">
                <div class="modal-content custom__modal__content">
                    <div class="modal__header d-flex justify-content-between align-items-center">
                        <h6 class="modal__title">Costing Attachment</h6>
                        <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                            data-bs-dismiss="modal" id="btnCloseModal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    @if ($ticket->ticketCosting->fileAttachments->count() === 0)
                        <div class="modal__body mt-3">
                            <form wire:submit.prevent="saveNewCostingFiles">
                                <div class="col-12 mt-auto">
                                    <div class="d-flex align-items-center gap-3">
                                        <label for="ticketSubject" class="form-label ticket__costing__label">
                                            Attachment
                                        </label>
                                    </div>
                                    <div x-data="{ isUploading: false, progress: 1 }"
                                        x-on:livewire-upload-start="isUploading = true; progress = 1"
                                        x-on:livewire-upload-finish="isUploading = false"
                                        x-on:livewire-upload-error="isUploading = false"
                                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                                        <input class="form-control form-control-sm border-0 costing__file__attachment"
                                            type="file" accept=".xlsx,.xls,image/*,.doc,.docx,.pdf,.csv"
                                            wire:model="newCostingFiles" multiple
                                            id="upload-{{ $uploadFileCostingCount }}"
                                            onchange="validateCotingFile()">
                                        <div x-transition.duration.500ms x-show="isUploading"
                                            class="progress progress-sm mt-1" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                role="progressbar" aria-label="Animated striped example"
                                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                                x-bind:style="`width: ${progress}%; background-color: #7e8da3;`">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between"
                                            x-transition.duration.500ms>
                                            <span x-show="isUploading" x-text="progress + '%'"
                                                style="font-size: 12px;">
                                            </span>
                                            <span class="d-flex align-items-center gap-1" style="font-size: 12px;">
                                                <i x-show="isUploading" class='bx bx-loader-circle bx-spin'
                                                    style="font-size: 14px;"></i>
                                                <span x-show="isUploading">Uploading...</span>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="error__message" id="excludeEXEfileMessage"></span>
                                    @error('newCostingFiles.*')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <button type="submit"
                                    class="btn mt-3 d-flex align-items-center justify-content-center gap-2 modal__footer__button modal__btnsubmit__bottom">
                                    <span wire:loading wire:target="saveNewCostingFiles"
                                        class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                    </span>
                                    Save
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-costing-file-preview-modal', () => {
            $('#costingPreviewFileAttachmentModal').modal('hide');
        });
        window.addEventListener('close-new-ticket-costing-file-modal', () => {
            $('#addCostingFileModal').modal('hide');
        });

        // Validate file
        function validateCotingFile() {
            const excludeEXEfileMessage = document.querySelector('#excludeEXEfileMessage');
            const costingFileInput = document.querySelector(`#upload-{{ $uploadFileCostingCount }}`);

            excludeEXEfileMessage.style.display = "none";

            const fileName = costingFileInput.value.split('\\').pop(); // Get the file name
            const allowedExtensions = @json($allowedExtensions);
            const fileExtension = fileName.split('.').pop().toLowerCase();

            // Check if the file extension is .exe
            if (!allowedExtensions.includes(fileExtension)) {
                excludeEXEfileMessage.style.display = "block";
                excludeEXEfileMessage.innerHTML =
                    '<i class="fa-solid fa-triangle-exclamation"></i> Invalid file type. File must be one of the following types: jpeg, jpg, png, pdf, doc, docx, xlsx, xls, csv';
                costingFileInput.value = ''; // Clear the file input
            } else {
                excludeEXEfileMessage.innerHTML = '';
            }
        }

        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' || event.keyCode === 27) {
                @this.set('editingFieldId', null);
            }
        });
    </script>
@endpush
