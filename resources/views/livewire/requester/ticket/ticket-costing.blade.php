<div wire:poll.visible.10s>
    @if (!is_null($ticket->ticketCosting))
        <div class="card border-0 p-0 card__ticket__details">
            <div class="row gap-2 justify-content-center ticket__costing__container">
                <div class="col-12">
                    <div class="d-flex gap-3 flex-wrap justify-content-between">
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label">
                                Actual Cost
                            </small>
                            <div class="d-flex align-items-center gap-1">
                                <span class="currency text-muted">₱</span>
                                <span class="amount">
                                    {{ $ticket->ticketCosting?->getAmount() }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label">Attachment</small>
                            @if ($ticket->ticketCosting->fileAttachments->count() > 0)
                                <small class="mb-1 mt-2 costing__labels show__costing__attachments"
                                    data-bs-toggle="modal" data-bs-target="#costingPreviewFileAttachmentModal">
                                    <i class="fa-solid fa-file-zipper"></i>
                                    {{ $ticket->ticketCosting->fileAttachments->count() }}
                                    attached
                                    {{ $ticket->ticketCosting->fileAttachments->count() > 1 ? 'files' : 'file' }}
                                </small>
                            @else
                                <small
                                    class="mt-1 d-flex align-items-baseline justify-content-center costing__labels btn__show__add__costing__file__modal">
                                    No attachments
                                </small>
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
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label">
                                Approver
                            </small>
                            <div class="d-flex align-items-center gap-1 costing__approver__container ">
                                @if ($this->costingApprovers($ticket))
                                    @foreach ($this->costingApprovers($ticket) as $costingApprover)
                                        @if ($costingApprover->profile->picture)
                                            <div class="d-flex position-relative">
                                                <img class="costing__approver__picture rounded-circle"
                                                    src="https://avatars.githubusercontent.com/u/63698615?s=400&u=49142410ee5c191a78412e36511c8b927fc6b1b1&v=4"
                                                    data-tooltip="{{ $costingApprover->profile->getFullName() }}  {{ $this->isDoneCostingApproval1($ticket) ? '(Approved)' : 'For approval' }}"
                                                    data-tooltip-position="top" data-tooltip-font-size="11px">
                                                @if (
                                                    $this->approvedByCostingApprover1($costingApprover, $ticket) ||
                                                        $this->approvedByCostingApprover2($costingApprover, $ticket))
                                                    <div class="position-absolute d-flex align-items-center justify-content-center rounded-circle costing__approver__approved__badge"
                                                        style="background-color: green">
                                                        <i class="bi bi-check-lg"></i>
                                                    </div>
                                                @else
                                                    <div class="position-absolute rounded-circle costing__approver__approved__badge bx-flashing"
                                                        style="background-color: #FFA500">
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="d-flex position-relative">
                                                <small
                                                    class="d-flex align-items-center justify-content-center gap-1 rounded-circle costing__approver__initial"
                                                    style="background-color: {{ $costingApprover->hasRole(App\Models\Role::SERVICE_DEPARTMENT_ADMIN) ? '#9DA85C' : '#3B4053' }}"
                                                    data-tooltip="{{ $costingApprover->profile->getFullName() }}  {{ $this->isDoneCostingApproval1($ticket) ? '(Approved)' : '(For approval)' }}"
                                                    data-tooltip-position="top" data-tooltip-font-size="11px">
                                                    {{ $costingApprover->profile->getNameInitial() }}
                                                </small>
                                                @if (
                                                    $this->approvedByCostingApprover1($costingApprover, $ticket) ||
                                                        $this->approvedByCostingApprover2($costingApprover, $ticket))
                                                    <div class="position-absolute d-flex align-items-center justify-content-center rounded-circle costing__approver__approved__badge"
                                                        style="background-color: green">
                                                        <i class="bi bi-check-lg"></i>
                                                    </div>
                                                @else
                                                    <div class="position-absolute rounded-circle costing__approver__approved__badge bx-flashing"
                                                        style="background-color: #FFA500">
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label">
                                Status
                            </small>
                            @if ($this->isDoneCostingApproval1($ticket))
                                @if ($this->isCostingAmountNeedCOOApproval($ticket) && !$this->isDoneCostingApproval2($ticket))
                                    <small
                                        class="d-flex align-items-center justify-content-center gap-1 rounded-4 text-dark approved__costing__status">
                                        <i class="fa-solid fa-paper-plane me-1" style="color: orange;"></i>
                                        For approval
                                    </small>
                                @else
                                    <small
                                        class="d-flex align-items-center justify-content-center gap-1 rounded-4 approved__costing__status">
                                        <i class="fa-solid fa-circle-check me-1" style="color: green;"></i>
                                        Approved
                                    </small>
                                @endif
                            @else
                                <small
                                    class="d-flex align-items-center justify-content-center gap-1 rounded-4 text-dark approved__costing__status">
                                    <i class="fa-solid fa-paper-plane me-1" style="color: orange;"></i>
                                    For approval
                                </small>
                            @endif
                        </div>
                        <div class="d-flex flex-column justify-content-between gap-2 position-relative">
                            <small class="text-muted text-sm costing__header__label">
                                Purchasing
                            </small>
                            @if (auth()->user()->hasRole(App\Models\Role::USER))
                                <small
                                    class="d-flex align-items-center justify-content-center gap-1 rounded-4 approved__costing__status">
                                    @if ($ticket->specialProjectStatus->purchasing_status)
                                        <i class="fa-solid fa-cart-arrow-down" style="color: green;"></i>
                                        {{ $ticket->specialProjectStatus->purchasing_status }}
                                    @else
                                        N/A
                                    @endif
                                </small>
                            @else
                                <small
                                    class="d-flex align-items-center justify-content-center gap-1 rounded-4 approved__costing__status">
                                    @if ($this->getPurchasingStatus() === \App\Enums\SpecialProjectStatusEnum::ON_ORDERED->value)
                                        <i class="fa-solid fa-cart-arrow-down" style="color: green;"></i>
                                        {{ \App\Enums\SpecialProjectStatusEnum::ON_ORDERED->value }}
                                    @elseif ($this->getPurchasingStatus() === \App\Enums\SpecialProjectStatusEnum::DELIVERED->value)
                                        <i class="fa-solid fa-truck" style="color: green;"></i>
                                        {{ \App\Enums\SpecialProjectStatusEnum::DELIVERED->value }}
                                    @else
                                        N/A
                                    @endif
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if ($this->isDoneSpecialProjectAmountApproval($ticket))
                <div class="row ticket__attach__pr__section">
                    <div class="col-12">
                        <div class="ticket__pr__content">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <span class="pr__section__label">
                                    You can now fill out and attach the PR for costing.
                                </span>
                                @if ($ticket->ticketCosting->prFileAttachments->count() > 0)
                                    <small
                                        class="mt-1 d-flex gap-1 align-items-baseline justify-content-center costing__labels btn__show__add__costing__pr__file__modal"
                                        data-bs-toggle="modal" data-bs-target="#addCostingPRFileModal">
                                        <i class="fa-solid fa-file-zipper"></i>
                                        {{ $ticket->ticketCosting->prFileAttachments->count() }}
                                        attached
                                        {{ $ticket->ticketCosting->prFileAttachments->count() > 1 ? 'files' : 'file' }}
                                    </small>
                                @else
                                    <small
                                        class="mt-1 d-flex gap-1 align-items-baseline justify-content-center costing__labels btn__show__add__costing__pr__file__modal"
                                        data-bs-toggle="modal" data-bs-target="#addCostingPRFileModal">
                                        <i class="bi bi-filetype-pdf"></i>
                                        Attach PR
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Preview Ticket Costing Files Modal -->
        <div wire:ignore.self class="modal fade ticket__costing__modal" tabindex="-1"
            id="costingPreviewFileAttachmentModal" aria-labelledby="modalFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered custom__modal">
                @if ($ticket->ticketCosting->fileAttachments->count() !== 0)
                    <div class="modal-content custom__modal__content">
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
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($this->isCurrentRequesterIsOwnerOfCosting())
        {{-- Add costing PR file --}}
        <div wire:ignore.self class="modal fade ticket__costing__modal" id="addCostingPRFileModal" tabindex="-1"
            aria-labelledby="modalFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered custom__modal">
                <div class="modal-content custom__modal__content">
                    <div class="modal__header d-flex justify-content-between align-items-center">
                        <h6 class="modal__title">Costing PR Attachment</h6>
                        <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                            data-bs-dismiss="modal" id="btnCloseModal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    @if (!$this->isDonePRFileApproval($ticket))
                        <div class="modal__body mt-3">
                            <form wire:submit.prevent="saveCostingPRFile">
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
                                            type="file" accept=".pdf" wire:model="costingPRFiles" multiple
                                            id="upload-{{ $uploadPRFileCostingCount }}"
                                            onchange="validatePRCostingFile()">
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
                                    <span class="error__message" id="excludeEXEfileMessageForPR"></span>
                                    @error('costingPRFiles')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <button type="submit"
                                    class="btn mt-3 d-flex align-items-center justify-content-center gap-2 modal__footer__button modal__btnsubmit__bottom">
                                    <span wire:loading wire:target="saveCostingPRFile"
                                        class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                    </span>
                                    Save
                                </button>
                            </form>
                        </div>
                    @endif
                    @if ($ticket->ticketCosting)
                        <div class="modal__body mt-3">
                            <ul class="list-group list-group-flush">
                                @foreach ($ticket->ticketCosting->prFileAttachments->sortByDesc('created_at') as $file)
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
                                            @if (!$this->isDonePRFileApproval($ticket))
                                                <a type="button" class="file__attachment__action__button"
                                                    wire:key="{{ $file->id }}"
                                                    wire:click="deleteCostingPRFile({{ $file->id }}, {{ $ticket }})">
                                                    <i class="bi bi-trash" wire:loading.class="d-none"
                                                        wire:target="deleteCostingPRFile({{ $file->id }}, {{ $ticket }})"></i>
                                                    <div wire:loading
                                                        wire:target="deleteCostingPRFile({{ $file->id }})"
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
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#addCostingPRFileModal').modal('hide');
        });

        // Validate file
        function validatePRCostingFile() {
            const excludeEXEfileMessageForPR = document.querySelector('#excludeEXEfileMessageForPR');
            const costingPRFileInput = document.querySelector(`#upload-{{ $uploadPRFileCostingCount }}`);
            excludeEXEfileMessageForPR.style.display = "none";

            const prFileName = costingPRFileInput.value.split('\\').pop(); // Get the file name
            const prAllowedExtensions = @json($prFileAllowedExtension);
            const prFileExtension = prFileName.split('.').pop().toLowerCase();
            console.log(prAllowedExtensions);
            // Check if the file extension is .exe
            if (!prAllowedExtensions.includes(prFileExtension)) {
                excludeEXEfileMessageForPR.style.display = "block";
                excludeEXEfileMessageForPR.innerHTML =
                    '<i class="fa-solid fa-triangle-exclamation"></i> Invalid file type. File must be one of the following types: pdf';
                costingPRFileInput.value = ''; // Clear the file input
            } else {
                excludeEXEfileMessageForPR.innerHTML = '';
            }
        }
    </script>
@endpush
