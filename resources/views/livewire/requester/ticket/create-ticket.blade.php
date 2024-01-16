<div>
    <div wire:ignore.self class="modal fade create__ticket__modal" id="createTicketModal" tabindex="-1"
        aria-labelledby="createtTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-lg">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="sendTicket">
                    <h1 class="modal-title modal__title fs-5 px-3">Create New Ticket</h1>
                    <div class="modal-body modal__body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check mt-2 mb-4">
                                    <input class="form-check-input" type="checkbox" id="checkOtherBranch">
                                    <label class="form-check-label labelCheckOtherBranch" for="checkOtherBranch">
                                        This ticket is intended to other branch
                                    </label>
                                    <br>
                                    <p class="mb-0 mt-1" style="font-size: 13px; line-height: 15px;">
                                        Check if your wish to send this ticket to other branch. Otherwise, leave
                                        unchecked to send this ticket to your currently assigned branch -
                                        <span class="fw-bold text-muted">
                                            <i class="fa-solid fa-location-dot"></i>
                                            {{ auth()->user()->getBranches() }}
                                        </span>.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12" id="branchSelectContainer" wire:ignore.self>
                                <div class="col-lg-6 col-md-12">
                                    <div wire:ignore.self class="mb-3">
                                        <label class="form-label input__field__label">
                                            To which branch will this ticket be sent?
                                        </label>
                                        <div>
                                            <div id="userCreateTicketBranchesDropdown" wire:ignore></div>
                                        </div>
                                        @error('branch')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label input__field__label">Service Department</label>
                                    <div>
                                        <div id="userCreateTicketServiceDepartmentDropdown" wire:ignore></div>
                                    </div>
                                    @error('serviceDepartment')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label input__field__label">
                                        Help Topic
                                        @if ($helpTopics)
                                            <span class="fw-normal" style="font-size: 13px;">
                                                ({{ $helpTopics->count() }})</span>
                                        @endif
                                    </label>
                                    <div>
                                        <div id="userCreateTicketHelpTopicDropdown" wire:ignore></div>
                                    </div>
                                    @error('helpTopic')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="ticketSubject" class="form-label input__field__label">
                                    Subject
                                    <span class="text-sm text-muted">
                                        <small>(Issue Summary)</small>
                                    </span>
                                </label>
                                <input type="text" wire:model="subject" class="form-control input__field"
                                    placeholder="Tell us about your concern">
                                @error('subject')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label input__field__label">
                                    Message
                                    <span class="text-sm text-muted">
                                        <small>(Tell us more about your concern)</small>
                                    </span>
                                </label>
                                <div wire:ignore>
                                    <textarea wire:model="description" id="createTicketDescription"></textarea>
                                </div>
                                @error('description')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="ticketSubject" class="form-label input__field__label">
                                        Priority Level
                                    </label>
                                    <div class="d-flex">
                                        @foreach ($priorityLevels as $priority)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                    wire:model="priorityLevel" id="rbtn{{ $priority->name }}"
                                                    value="{{ $priority->id }}">
                                                <label class="form-check-label radio__button__label"
                                                    for="rbtn{{ $priority->name }}">
                                                    {{ $priority->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('priorityLevel')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mt-auto">
                                    <div class="d-flex align-items-center gap-3">
                                        <label for="ticketSubject" class="form-label input__field__label">
                                            Attachment
                                        </label>
                                    </div>
                                    <div x-data="{ isUploading: false, progress: 1 }"
                                        x-on:livewire-upload-start="isUploading = true; progress = 1"
                                        x-on:livewire-upload-finish="isUploading = false"
                                        x-on:livewire-upload-error="isUploading = false"
                                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                                        <input class=" form-control form-control-sm border-0 ticket__file"
                                            type="file" accept=".xlsx,.xls,image/*,.doc,.docx,.pdf,.csv"
                                            wire:model="fileAttachments" multiple id="upload-{{ $upload }}"
                                            onchange="validateFile()">
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
                                    @error('fileAttachments.*')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 p-3">
                        <button type="button" class="btn ticket__modal__button btn__close__ticket__modal"
                            data-bs-dismiss="modal" wire:click="cancel">Cancel</button>
                        <button type="submit"
                            class="btn d-flex align-items-center justify-content-center gap-2 ticket__modal__button">
                            <span wire:loading wire:target="sendTicket" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Send Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('livewire-textarea')
    <script>
        tinymce.init({
            selector: '#createTicketDescription',
            plugins: 'lists',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist',
            height: 350,
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
                editor.on('change', function(e) {
                    @this.set('description', editor.getContent());
                });
            }
        });
    </script>
@endpush

@push('livewire-select')
    <script>
        const serviceDepartmentOption = [
            @foreach ($serviceDepartments as $serviceDepartment)
                {
                    label: "{{ $serviceDepartment->name }}",
                    value: "{{ $serviceDepartment->id }}"
                },
            @endforeach
        ];
        const serviceDepartmentSelect = document.querySelector('#userCreateTicketServiceDepartmentDropdown');
        VirtualSelect.init({
            ele: serviceDepartmentSelect,
            options: serviceDepartmentOption,
            search: true,
            markSearchResults: true,
        });

        const helpTopicSelect = document.querySelector('#userCreateTicketHelpTopicDropdown');
        VirtualSelect.init({
            ele: helpTopicSelect,
            search: true,
            markSearchResults: true,
        });
        helpTopicSelect.disable();

        const team = document.querySelector('#team');
        const sla = document.querySelector('#sla');

        serviceDepartmentSelect.addEventListener('change', () => {
            const serviceDepartmentId = serviceDepartmentSelect.value;
            if (serviceDepartmentId) {
                @this.set('serviceDepartment', parseInt(serviceDepartmentId));
                helpTopicSelect.enable();
                window.addEventListener('get-help-topics-from-service-department', (event) => {
                    const helpTopics = event.detail.helpTopics;
                    const helpTopicOption = [];

                    if (helpTopics.length > 0) {
                        helpTopics.forEach(function(helpTopic) {
                            VirtualSelect.init({
                                ele: helpTopicSelect
                            });

                            helpTopicOption.push({
                                label: helpTopic.name,
                                value: helpTopic.id
                            });
                        });
                        helpTopicSelect.setOptions(helpTopicOption);
                    } else {
                        helpTopicSelect.setOptions([]);
                        helpTopicSelect.disable();
                    }
                });
            }
        });

        helpTopicSelect.addEventListener('change', () => {
            @this.set('helpTopic', parseInt(helpTopicSelect.value))
        });

        const branchSelect = document.querySelector('#userCreateTicketBranchesDropdown');
        const branchOption = [
            @foreach ($branches as $branch)
                {
                    label: "{{ $branch->name }}",
                    value: "{{ $branch->id }}"
                },
            @endforeach
        ];
        VirtualSelect.init({
            ele: '#userCreateTicketBranchesDropdown',
            options: branchOption,
            search: true,
            markSearchResults: true,
        });

        branchSelect.addEventListener('change', () => {
            @this.set('branch', parseInt(branchSelect.value));
        });

        const selectOtherBranch = document.querySelector('#checkOtherBranch');
        const branchSelectContainer = document.querySelector('#branchSelectContainer');
        branchSelect.disable();
        branchSelectContainer.style.display = 'none';

        if (selectOtherBranch) {
            selectOtherBranch.addEventListener('change', (e) => {
                if (e.target.checked) {
                    branchSelect.enable();
                    branchSelectContainer.style.display = 'block';
                } else {
                    branchSelect.reset();
                    branchSelect.disable();
                    branchSelectContainer.style.display = 'none';
                    @this.set('branch', null);
                }
            });

            window.addEventListener('clear-branch-dropdown-select', () => {
                if (selectOtherBranch.checked) {
                    selectOtherBranch.checked = false;
                    @this.set('branch', null);
                }
            });
        }

        window.addEventListener('clear-select-dropdown', () => {
            branchSelect.reset();
            helpTopicSelect.reset();
            serviceDepartmentSelect.reset();
            helpTopicSelect.disable();
            tinymce.get("createTicketDescription").setContent("");

            if (selectOtherBranch.checked) {
                selectOtherBranch.checked = false;
                branchSelectContainer.style.display = 'none';
                @this.set('branch', null);
            }
        });

        // Validate file
        function validateFile() {
            const excludeEXEfileMessage = document.querySelector('#excludeEXEfileMessage');
            const fileInput = document.querySelector(`#upload-{{ $upload }}`);

            excludeEXEfileMessage.style.display = "none";

            const fileName = fileInput.value.split('\\').pop(); // Get the file name
            const allowedExtensions = ['jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx', 'xlsx', 'xls', 'csv'];
            const fileExtension = fileName.split('.').pop().toLowerCase();

            // Check if the file extension is .exe
            if (!allowedExtensions.includes(fileExtension)) {
                excludeEXEfileMessage.style.display = "block";
                excludeEXEfileMessage.innerHTML =
                    '<i class="fa-solid fa-triangle-exclamation"></i> Invalid file type. File must be one of the following types: jpeg, jpg, png, pdf, doc, docx, xlsx, xls, csv';
                fileInput.value = ''; // Clear the file input
            } else {
                fileValidationMessage.innerHTML = '';
            }
        }
    </script>
@endpush


@push('livewire-modal')
    <script>
        const refreshServiceDepartmentOption = [
            @foreach ($serviceDepartments as $serviceDepartment)
                {
                    label: "{{ $serviceDepartment->name }}",
                    value: "{{ $serviceDepartment->id }}"
                },
            @endforeach
        ];
        window.addEventListener('close-modal', () => {
            $('#createTicketModal').modal('hide');
            tinymce.get("createTicketDescription").setContent("");
            serviceDepartmentSelect.reset();
            helpTopicSelect.reset();
            branchSelect.reset();
            helpTopicSelect.disable();
            helpTopicSelect.setOptions([]);
        });
    </script>
@endpush
