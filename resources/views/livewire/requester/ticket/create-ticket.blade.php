@php
    use App\Enums\FieldTypesEnum as FieldType;
@endphp

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
                                    <input class="form-check-input" type="checkbox" id="check-other-branch">
                                    <label class="form-check-label labelCheckOtherBranch" for="check-other-branch">
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
                            <div class="col-12" id="branch-select-container" wire:ignore.self>
                                <div class="col-lg-6 col-md-12">
                                    <div wire:ignore.self class="mb-3">
                                        <label class="form-label input__field__label">
                                            To which branch will this ticket be sent?
                                        </label>
                                        <div>
                                            <div id="user-create-ticket-branches-dropdown" wire:ignore></div>
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
                                        <div id="user-create-ticket-service-department-dropdown" wire:ignore></div>
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
                                                ({{ $helpTopics->count() }})
                                            </span>
                                        @endif
                                    </label>
                                    <div>
                                        <div id="user-create-ticket-help-topic-dropdown" wire:ignore></div>
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
                            <div class="mb-3" id="ticket-description-container" wire:ignore>
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

                            {{-- Help topic form --}}
                            @if ($helpTopicForm)
                                <div class="col-12">
                                    <div class="row mb-3 pb-4 mx-auto ps-1 rounded-3 custom__form">
                                        <div class="d-flex align-items-center justify-content-between flex-row mb-3">
                                            <h6 class="fw-bold mt-2 mb-0 text-end mt-4 form__name">
                                                {{ $helpTopicForm->name }}
                                            </h6>
                                            {{-- <img src="{{ asset('images/gmall-davao-pr-form.png') }}"
                                                class="pr__form__gmall__logo mt-3" alt="GMall Ticketing System"> --}}
                                        </div>
                                        @if (!empty($headerFields))
                                            <div class="row mx-auto my-3">
                                                @foreach ($headerFields as $hfKey => $headerField)
                                                    @if ($headerField['assigned_column'] == 1)
                                                        <div
                                                            class="col-lg-6 col-md-12 col-sm-12 ps-0 pe-lg-4 pe-md-0 mb-2">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <label class="form-label mb-0 input__field__label"
                                                                    style="white-space: nowrap">
                                                                    {{ $headerField['label'] }}:
                                                                </label>
                                                                {{-- Text field --}}
                                                                @if ($headerField['type'] === FieldType::TEXT->value)
                                                                    <input
                                                                        wire:model="headerFields.{{ $hfKey }}.value"
                                                                        type="text" id="field-{{ $hfKey }}"
                                                                        class="w-100 px-0 py-0 border-0 rounded-0 form-control input__field header__field "
                                                                        placeholder="Enter {{ Str::lower($headerField['label']) }}">
                                                                @endif

                                                                {{-- Number field --}}
                                                                @if ($headerField['type'] === FieldType::NUMBER->value)
                                                                    <input
                                                                        wire:model="headerFields.{{ $hfKey }}.value"
                                                                        type="number" id="field-{{ $hfKey }}"
                                                                        class="w-100 px-0 py-0 border-0 rounded-0 form-control input__field header__field"
                                                                        placeholder="Enter {{ Str::lower($headerField['label']) }}">
                                                                @endif

                                                                {{-- Date field --}}
                                                                @if ($headerField['type'] === FieldType::DATE->value)
                                                                    <input
                                                                        wire:model="headerFields.{{ $hfKey }}.value"
                                                                        type="date" id="field-{{ $hfKey }}"
                                                                        class="w-100 px-0 py-0 border-0 rounded-0 form-control input__field header__field"
                                                                        placeholder="Enter {{ Str::lower($headerField['label']) }}">
                                                                @endif

                                                                {{-- Time field --}}
                                                                @if ($headerField['type'] === FieldType::TIME->value)
                                                                    <input
                                                                        wire:model="headerFields.{{ $hfKey }}.value"
                                                                        type="time" id="field-{{ $hfKey }}"
                                                                        class="w-100 px-0 py-0 border-0 rounded-0 form-control input__field header__field"
                                                                        placeholder="Enter {{ Str::lower($headerField['label']) }}">
                                                                @endif

                                                                {{-- Amount field --}}
                                                                @if ($headerField['type'] === FieldType::AMOUNT->value)
                                                                    <input
                                                                        wire:model="headerFields.{{ $hfKey }}.value"
                                                                        type="number" id="field-{{ $hfKey }}"
                                                                        class="w-100 px-0 py-0 border-0 rounded-0 form-control input__field header__field"
                                                                        placeholder="Enter {{ Str::lower($headerField['label']) }}">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($headerField['assigned_column'] == 2)
                                                        <div class="col-lg-6 col-md-12 col-sm-12 px-0 mb-2">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <label class="form-label mb-0 input__field__label"
                                                                    style="white-space: nowrap">
                                                                    {{ $headerField['label'] }}:
                                                                </label>
                                                                {{-- Text field --}}
                                                                @if ($headerField['type'] === FieldType::TEXT->value)
                                                                    <input
                                                                        wire:model="headerFields.{{ $hfKey }}.value"
                                                                        type="text" id="field-{{ $hfKey }}"
                                                                        class="w-100 px-0 py-0 border-0 rounded-0 form-control input__field header__field"
                                                                        placeholder="Enter {{ Str::lower($headerField['label']) }}">
                                                                @endif

                                                                {{-- Number field --}}
                                                                @if ($headerField['type'] === FieldType::NUMBER->value)
                                                                    <input
                                                                        wire:model="headerFields.{{ $hfKey }}.value"
                                                                        type="number" id="field-{{ $hfKey }}"
                                                                        class="w-100 px-0 py-0 border-0 rounded-0 form-control input__field header__field"
                                                                        placeholder="Enter {{ Str::lower($headerField['label']) }}">
                                                                @endif

                                                                {{-- Date field --}}
                                                                @if ($headerField['type'] === FieldType::DATE->value)
                                                                    <input
                                                                        wire:model="headerFields.{{ $hfKey }}.value"
                                                                        type="date" id="field-{{ $hfKey }}"
                                                                        class="w-100 px-0 py-0 border-0 rounded-0 form-control input__field header__field"
                                                                        placeholder="Enter {{ Str::lower($headerField['label']) }}">
                                                                @endif

                                                                {{-- Time field --}}
                                                                @if ($headerField['type'] === FieldType::TIME->value)
                                                                    <input
                                                                        wire:model="headerFields.{{ $hfKey }}.value"
                                                                        type="time" id="field-{{ $hfKey }}"
                                                                        class="w-100 px-0 py-0 border-0 rounded-0 form-control input__field header__field"
                                                                        placeholder="Enter {{ Str::lower($headerField['label']) }}">
                                                                @endif

                                                                {{-- Amount field --}}
                                                                @if ($headerField['type'] === FieldType::AMOUNT->value)
                                                                    <input
                                                                        wire:model="headerFields.{{ $hfKey }}.value"
                                                                        type="number" id="field-{{ $hfKey }}"
                                                                        class="w-100 px-0 py-0 border-0 rounded-0 form-control input__field header__field"
                                                                        placeholder="Enter {{ Str::lower($headerField['label']) }}">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="w-100 d-flex flex-row flex-xl-nowrap flex-lg-nowrap flex-sm-wrap">
                                            @foreach ($nonHeaderFields as $key => $field)
                                                @if ($field['is_enabled'])
                                                    <div class="d-flex flex-column border w-100">
                                                        <div class="form__label border-bottom px-2 py-1">
                                                            <label for="field-{{ $key }}"
                                                                class="input__field__label">
                                                                {{ Str::title($field['label']) }}
                                                            </label>
                                                        </div>
                                                        @foreach ($fieldRows as $rowKey => $fieldRow)
                                                            @if ($field['name'] == $fieldRow['name'])
                                                                <div class="form__field"
                                                                    id="field-row-{{ $rowKey }}">
                                                                    {{-- Text field --}}
                                                                    @if ($field['type'] === FieldType::TEXT->value)
                                                                        <input
                                                                            wire:model="fieldRows.{{ $rowKey }}.value"
                                                                            type="text"
                                                                            id="field-{{ $rowKey }}"
                                                                            class="w-100 px-2 py-1 border-0 rounded-0 form-control input__field custom__field"
                                                                            placeholder="Enter {{ Str::lower($field['label']) }}">
                                                                    @endif

                                                                    {{-- Number field --}}
                                                                    @if ($field['type'] === FieldType::NUMBER->value)
                                                                        <input
                                                                            wire:model="fieldRows.{{ $rowKey }}.value"
                                                                            type="number"
                                                                            id="field-{{ $rowKey }}"
                                                                            class="w-100 px-2 py-1 border-0 rounded-0 form-control input__field custom__field"
                                                                            placeholder="Enter {{ Str::lower($field['label']) }}">
                                                                    @endif

                                                                    {{-- Date field --}}
                                                                    @if ($field['type'] === FieldType::DATE->value)
                                                                        <input
                                                                            wire:model="fieldRows.{{ $rowKey }}.value"
                                                                            type="date"
                                                                            id="field-{{ $rowKey }}"
                                                                            class="w-100 px-2 py-1 border-0 rounded-0 form-control input__field custom__field"
                                                                            placeholder="Enter {{ Str::lower($field['label']) }}">
                                                                    @endif

                                                                    {{-- Time field --}}
                                                                    @if ($field['type'] === FieldType::TIME->value)
                                                                        <input
                                                                            wire:model="fieldRows.{{ $rowKey }}.value"
                                                                            type="time"
                                                                            id="field-{{ $rowKey }}"
                                                                            class="w-100 px-2 py-1 border-0 rounded-0 form-control input__field custom__field"
                                                                            placeholder="Enter {{ Str::lower($field['label']) }}">
                                                                    @endif

                                                                    {{-- Amount field --}}
                                                                    @if ($field['type'] === FieldType::AMOUNT->value)
                                                                        <input
                                                                            wire:model="fieldRows.{{ $rowKey }}.value"
                                                                            type="number"
                                                                            id="field-{{ $rowKey }}"
                                                                            class="w-100 px-2 py-1 border-0 rounded-0 form-control input__field custom__field"
                                                                            placeholder="Enter {{ Str::lower($field['label']) }}">
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="new__field__row position-relative">
                                            <div class="row__line"></div>
                                            <button wire:click="addFieldRow" type="button" id="add-field-row"
                                                class="btn btn-sm d-flex align-items-center justify-content-center position-absolute btn__add__new__field__row">
                                                <i class="bi bi-plus-lg" wire:loading.remove
                                                    wire:target="addFieldRow"></i>
                                                <i wire:target="addFieldRow" wire:loading
                                                    class='bx bx-loader-alt bx-spin'></i>
                                            </button>
                                        </div>
                                        {{-- <div class="d-flex align-items-center gap-2 mt-2">
                                            <button wire:click="addFieldRow" type="button" class="btn btn-sm">
                                                Insert fields
                                            </button>
                                        </div> --}}
                                    </div>
                                </div>
                            @endif
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
                                <div wire:ignore class="col-md-4 mt-auto" id="ticket-file-attachment-container">
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
                                        <input class="form-control form-control-sm border-0 ticket__file"
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
                                    <span class="error__message" id="exclude-exe-file-message"></span>
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
                            <span wire:loading.remove wire:target="sendTicket">Send Ticket</span>
                            <span wire:loading wire:target="sendTicket">Sending...</span>
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
                editor.on('change', function() {
                    @this.set('description', editor.getContent());
                });
            }
        });
    </script>
@endpush

@push('livewire-select')
    <script>
        const serviceDepartmentOption = @json($serviceDepartments).map(serviceDepartment => ({
            label: serviceDepartment.name,
            value: serviceDepartment.id
        }));

        const serviceDepartmentSelect = document.querySelector('#user-create-ticket-service-department-dropdown');
        VirtualSelect.init({
            ele: serviceDepartmentSelect,
            options: serviceDepartmentOption,
            search: true,
            markSearchResults: true,
        });

        const helpTopicSelect = document.querySelector('#user-create-ticket-help-topic-dropdown');
        VirtualSelect.init({
            ele: helpTopicSelect,
            search: true,
            markSearchResults: true,
            hideClearButton: true
        });
        helpTopicSelect.disable();

        const team = document.querySelector('#team');
        const sla = document.querySelector('#sla');

        serviceDepartmentSelect.addEventListener('change', (event) => {
            const serviceDepartmentId = event.target.value;
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

        serviceDepartmentSelect.addEventListener('reset', () => {
            helpTopicSelect.reset();
            helpTopicSelect.disable();
            helpTopicSelect.setOptions([]);
            @this.set('helpTopicForm', null);
            @this.set('filledForms', []);
        });

        const ticketDescriptionContainer = document.querySelector('#ticket-description-container');
        helpTopicSelect.addEventListener('change', (event) => {
            @this.set('helpTopic', parseInt(event.target.value));

            window.addEventListener('show-help-topic-forms', () => {
                ticketDescriptionContainer.style.display = 'none';
            });

            window.addEventListener('hide-ticket-description-container', () => {
                ticketDescriptionContainer.style.display = 'block';
                @this.set('helpTopicForm', null);
            });
        });

        helpTopicSelect.addEventListener('reset', () => {
            ticketDescriptionContainer.style.display = 'block';
        });

        const branchSelect = document.querySelector('#user-create-ticket-branches-dropdown');
        const branchOption = @json($branches).map(branch => ({
            label: branch.name,
            value: branch.id
        }));

        VirtualSelect.init({
            ele: branchSelect,
            options: branchOption,
            search: true,
            markSearchResults: true,
        });

        branchSelect.addEventListener('change', (event) => {
            @this.set('branch', parseInt(event.target.value));
        });

        const selectOtherBranch = document.querySelector('#check-other-branch');
        const branchSelectContainer = document.querySelector('#branch-select-container');
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

        const ticketFileAttachmentContainer = document.querySelector('#ticket-file-attachment-container');
        window.addEventListener('hide-ticket-file-attachment-field-container', () => {
            ticketFileAttachmentContainer.style.display = 'none';
        });

        window.addEventListener('show-ticket-file-attachment-field-container', () => {
            ticketFileAttachmentContainer.style.display = 'block';
        });

        // Validate file
        function validateFile() {
            const excludeEXEfileMessage = document.querySelector('#exclude-exe-file-message');
            const fileInput = document.querySelector(`#upload-{{ $upload }}`);

            excludeEXEfileMessage.style.display = "none";

            const fileName = fileInput.value.split('\\').pop(); // Get the file name
            const allowedExtensions = @json($allowedExtensions);
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
        const refreshServiceDepartmentOption = @json($serviceDepartments).map(serviceDepartment => ({
            label: serviceDepartment.name,
            value: serviceDepartment.id
        }));

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
