<div>
    <div wire:ignore.self class="modal fade ticket__costing__modal" id="addCostingModal" tabindex="-1"
        aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom__modal">
            <div class="modal-content custom__modal__content">
                <div class="modal__header d-flex justify-content-between align-items-center">
                    <h6 class="modal__title">Add Costing</h6>
                    <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                        data-bs-dismiss="modal" id="btnCloseModal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal__body">
                    <form wire:submit.prevent="saveCosting">
                        <div class="my-2">
                            <label class="ticket__costing__label mb-2" for="amount">Enter project cost</label>
                            <input type="text" wire:model.defer="amount" class="form-control form__field"
                                id="amount" placeholder="00.00">
                            @error('amount')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="col-12 mt-auto">
                            <div class="d-flex align-items-center gap-3">
                                <label for="ticketSubject" class="form-label ticket__costing__label">
                                    Attachment
                                </label>
                            </div>
                            <div x-data="{ isUploading: false, progress: 1 }" x-on:livewire-upload-start="isUploading = true; progress = 1"
                                x-on:livewire-upload-finish="isUploading = false"
                                x-on:livewire-upload-error="isUploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress">
                                <input class="form-control form-control-sm border-0 costing__file__attachment"
                                    type="file" accept=".xlsx,.xls,image/*,.doc,.docx,.pdf,.csv"
                                    wire:model="costingFiles" multiple id="upload-{{ $uploadCostingCount }}"
                                    onchange="validateCotingFile()">
                                <div x-transition.duration.500ms x-show="isUploading" class="progress progress-sm mt-1"
                                    style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                        role="progressbar" aria-label="Animated striped example" aria-valuenow="75"
                                        aria-valuemin="0" aria-valuemax="100"
                                        x-bind:style="`width: ${progress}%; background-color: #7e8da3;`">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between"
                                    x-transition.duration.500ms>
                                    <span x-show="isUploading" x-text="progress + '%'" style="font-size: 12px;">
                                    </span>
                                    <span class="d-flex align-items-center gap-1" style="font-size: 12px;">
                                        <i x-show="isUploading" class='bx bx-loader-circle bx-spin'
                                            style="font-size: 14px;"></i>
                                        <span x-show="isUploading">Uploading...</span>
                                    </span>
                                </div>
                            </div>
                            <span class="error__message" id="excludeEXEfileMessage"></span>
                            @error('costingFiles.*')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="btn mt-3 d-flex align-items-center justify-content-center gap-2 modal__footer__button modal__btnsubmit__bottom">
                            <span wire:loading wire:target="saveCosting" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
    <script>
        // Validate file
        function validateCotingFile() {
            const excludeEXEfileMessage = document.querySelector('#excludeEXEfileMessage');
            const costingFileInput = document.querySelector(`#upload-{{ $uploadCostingCount }}`);

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
                fileValidationMessage.innerHTML = '';
            }
        }
    </script>
@endpush

@push('livewire-modal')
    <script>
        window.addEventListener('close-costing-modal', () => {
            $('#addCostingModal').modal('hide');
        });
    </script>
@endpush
