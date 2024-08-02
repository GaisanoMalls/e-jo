@php
    use App\Models\Role;
    use App\Enums\FieldTypesEnum as FieldType;
    use App\Enums\ApprovalStatusEnum as Status;
@endphp

<div class="mb-4">
    @if (
        $this->isRecommendationRequested() &&
            auth()->user()->hasRole(Role::USER))
        @if ($this->isTicketIctRecommendationIsApproved())
            <div class="alert d-inline-block mb-4 gap-1 border-0 py-2 px-3" role="alert"
                style="font-size: 13px; background-color: #dffdef;">
                <i class="bi bi-check-circle-fill" style="color: #d32839;"></i>
                The recommendation has been approved
            </div>
        @else
            <div class="mb-4 d-flex flex-wrap gap-2 border-0 flex-row rounded-3 align-items-center justify-content-between p-3"
                style="margin-left: 1px; margin-right: 1px; box-shadow: rgba(17, 17, 26, 0.1) 0px 4px 16px, rgba(17, 17, 26, 0.05) 0px 8px 32px;">
                <span class="border-0 d-flex align-items-center" style="font-size: 0.9rem;">
                    <span class="me-2">
                        <div class="d-flex align-items-center">
                            @if ($ictRecommendationServiceDeptAdmin->requestedByServiceDeptAdmin?->profile->picture)
                                <img src="{{ Storage::url($ictRecommendationServiceDeptAdmin?->requestedByServiceDeptAdmin->profile->picture) }}"
                                    class="image-fluid rounded-circle"
                                    style="height: 26px !important; width: 26px !important;">
                            @else
                                <div class="d-flex align-items-center p-2 me-1 justify-content-center text-white rounded-circle"
                                    style="background-color: #196837; height: 26px !important; width: 26px !important; font-size: 0.7rem;">
                                    {{ $ictRecommendationServiceDeptAdmin->requestedByServiceDeptAdmin?->profile->getNameInitial() }}
                                </div>
                            @endif
                            <strong class="text-muted">
                                {{ $ictRecommendationServiceDeptAdmin->requestedByServiceDeptAdmin?->profile->getFullName }}
                            </strong>
                        </div>
                    </span>
                    is requesting for recommendation approval
                </span>
            </div>
        @endif
    @endif
    <div wire:init="loadCustomFormFields" class="row">
        @if ($customFormFields->isNotEmpty())
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                <h6 class="mb-0 custom__form__name">{{ $ticket->helpTopic->form->name }}</h6>
                @if ($ticket->approval_status === Status::FOR_APPROVAL)
                    <div class="d-flex align-items-center gap-2">
                        <button wire:click="editCustomForm({{ $ticket->helpTopic->form->id }})"
                            class="btn d-flex align-items-center justify-content-center gap-2 btn-sm btn__edit__custom__form"
                            @style(['color: red' => $isEditing])>
                            @if ($isEditing)
                                Cancel
                            @else
                                Edit
                            @endif
                        </button>
                        @if ($isEditing)
                            <button wire:click="updateCustomForm({{ $ticket->helpTopic->form->id }})"
                                class="btn d-flex align-items-center justify-content-center gap-2 btn-sm btn__edit__custom__form"
                                @style(['background-color: #d32839' => $isEditing, 'color: #FFFFFF' => $isEditing])>
                                Update
                            </button>
                        @endif
                    </div>
                @endif
            </div>
            @foreach ($customFormFields as $key => $field)
                {{-- Display those fields that are set to enabled. --}}
                @if ($field['is_enabled'])
                    {{-- short text field --}}
                    @if ($field['type'] === FieldType::SHORT_ANSWER->value)
                        <div class="col-md-6 mb-3">
                            <label for="field-{{ $key }}" class="form-label input__field__label">
                                {{ Str::title($field['label']) }}
                            </label>
                            <input wire:model="customFormFields.{{ $key }}.value" type="text"
                                id="field-{{ $key }}" class="form-control input__field"
                                placeholder="Enter {{ Str::lower($field['label']) }}" @required($field['is_required'])
                                @disabled(!$isEditing)>
                            @error('customcustomFormFields.{{ $key }}.value')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif

                    {{-- long text field --}}
                    @if ($field['type'] === FieldType::LONG_ANSWER->value)
                        <div class="col-md-6 mb-3">
                            <label for="field-{{ $key }}" class="form-label input__field__label">
                                {{ Str::title($field['label']) }}
                            </label>
                            <textarea wire:model="customFormFields.{{ $key }}.value" id="field-{{ $key }}"
                                class="form-control input__field" placeholder="Enter {{ Str::lower($field['label']) }}" @required($field['is_required'])
                                @disabled(!$isEditing)>
                                                </textarea>
                            @error('customFormFields.{{ $key }}.value')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif

                    {{-- number field --}}
                    @if ($field['type'] === FieldType::NUMBER->value)
                        <div class="col-md-6 mb-3">
                            <label for="field-{{ $key }}" class="form-label input__field__label">
                                {{ Str::title($field['label']) }}
                            </label>
                            <input wire:model="customFormFields.{{ $key }}.value"
                                id="field-{{ $key }}" type="number" class="form-control input__field"
                                placeholder="Enter {{ Str::lower($field['label']) }}" @required($field['is_required'])
                                @disabled(!$isEditing)>
                            </input>
                            @error('customFormFields.{{ $key }}.value')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif

                    {{-- date field --}}
                    @if ($field['type'] === FieldType::DATE->value)
                        <div class="col-md-6 mb-3">
                            <label for="field-{{ $key }}" class="form-label input__field__label">
                                {{ Str::title($field['label']) }}
                            </label>
                            <input wire:model="customFormFields.{{ $key }}.value"
                                id="field-{{ $key }}" type="date" class="form-control input__field"
                                placeholder="Enter {{ Str::lower($field['label']) }}" @required($field['is_required'])
                                @disabled(!$isEditing)>
                            </input>
                            @error('customFormFields.{{ $key }}.value')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif

                    {{-- time field --}}
                    @if ($field['type'] === FieldType::TIME->value)
                        <div class="col-md-6 mb-3">
                            <label for="field-{{ $key }}" class="form-label input__field__label">
                                {{ Str::title($field['label']) }}
                            </label>
                            <input wire:model="customFormFields.{{ $key }}.value"
                                id="field-{{ $key }}" type="time" class="form-control input__field"
                                placeholder="Enter {{ Str::lower($field['label']) }}" @required($field['is_required'])
                                @disabled(!$isEditing)>
                            </input>
                            @error('customFormFields.{{ $key }}.value')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif

                    {{-- amount field --}}
                    @if ($field['type'] === FieldType::AMOUNT->value)
                        <div class="col-md-6 mb-3">
                            <label for="field-{{ $key }}" class="form-label input__field__label">
                                {{ Str::title($field['label']) }}
                            </label>
                            <input wire:model="customFormFields.{{ $key }}.value"
                                id="field-{{ $key }}" type="number" step=".01"
                                class="form-control input__field" placeholder="Enter {{ Str::lower($field['label']) }}"
                                @disabled(!$isEditing)>
                            </input>
                            @error('customFormFields.{{ $key }}.value')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif

                    {{-- file upload field --}}
                    @if ($isEditing)
                        @if ($field['type'] === FieldType::FILE->value)
                            <div class="col-md-6 mb-3">
                                <label for="field-{{ $key }}" class="form-label input__field__label">
                                    {{ Str::title($field['label']) }}
                                </label>
                                <div x-data="{ isUploadingCustomFormFile: false, progress: 1 }"
                                    x-on:livewire-upload-start="isUploadingCustomFormFile = true; progress = 1"
                                    x-on:livewire-upload-finish="isUploadingCustomFormFile = false"
                                    x-on:livewire-upload-error="isUploadingCustomFormFile = false"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                                    <input wire:model="customFormFieldFiles" id="field-{{ $key }}"
                                        type="file"
                                        class="form-control form-control-sm border-0 custom__form__attachment__input"
                                        placeholder="Enter {{ Str::lower($field['label']) }}"
                                        accept=".xlsx,.xls,image/*,.doc,.docx,.pdf,.csv" multiple
                                        @disabled(!$isEditing)>
                                    </input>
                                    <div x-transition.duration.500ms x-show="isUploadingCustomFormFile"
                                        class="progress progress-sm mt-1" style="height: 10px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                            role="progressbar" aria-label="Animated striped example"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                            x-bind:style="`width: ${progress}%; background-color: #7e8da3;`">
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between"
                                        x-transition.duration.500ms>
                                        <span x-show="isUploadingCustomFormFile" x-text="progress + '%'"
                                            style="font-size: 12px;">
                                        </span>
                                        <span class="d-flex align-items-center gap-1" style="font-size: 12px;">
                                            <i x-show="isUploadingCustomFormFile" class='bx bx-loader-circle bx-spin'
                                                style="font-size: 14px;"></i>
                                            <span x-show="isUploadingCustomFormFile">Uploading...</span>
                                        </span>
                                    </div>
                                </div>
                                @error('customFormFields.{{ $key }}.value')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        @endif
                    @endif
                @endif
            @endforeach
        @else
            <div class="d-flex justify-content-center">
                <div class="d-flex gap-2 flex-column align-items-center justify-content-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <small>Loading...</small>
                </div>
            </div>
        @endif
    </div>
    @if ($customFormImageFiles->isNotEmpty() || $customFormDocumentFiles->isNotEmpty())
        <label class="form-label input__field__label">
            @php
                $totalFiles = $customFormImageFiles->count() + $customFormDocumentFiles->count();
            @endphp
            File {{ $totalFiles === 1 ? 'Attachment' : 'Attachments' }}
            <small>
                ({{ $totalFiles }}
                {{ $totalFiles === 1 ? 'item' : 'items' }})
            </small>
        </label>
        <div class="d-flex flex-column gap-4">
            @if ($customFormImageFiles->isNotEmpty())
                <div class="d-flex flex-column gap-2">
                    <small class="fw-bold">Images</small>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($customFormImageFiles as $key => $imageFile)
                            <div wire:key="image-{{ $key }}"
                                class="custom__form__image__container position-relative">
                                <a href="{{ Storage::url($imageFile->file_attachment) }}" target="_blank">
                                    <img src="{{ Storage::url($imageFile->file_attachment) }}"
                                        class="custom__form__file__attachment__image">
                                </a>
                                <div
                                    class="w-100 d-flex align-items-center justify-content-center position-absolute custom__form__image__action__buttons__container">
                                    <button wire:key="delete-{{ $key }}"
                                        wire:click="deleteCustomFormFile({{ $imageFile->id }})"
                                        class="btn btn-sm d-flex align-items-center justify-content-center btn__delete__custom__form__file">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <a wire:prevent href="{{ Storage::url($imageFile->file_attachment) }}"
                                        type="button"
                                        class="btn btn-sm d-flex align-items-center justify-content-center btn__delete__custom__form__file"
                                        download>
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($customFormDocumentFiles->isNotEmpty())
                <div class="row gap-2">
                    <small class="fw-bold">Documents</small>
                    @foreach ($customFormDocumentFiles as $key => $documentFile)
                        <div wire:key="document-{{ $key }}"
                            class="w-auto d-inline-flex align-items-center position-relative mb-1">
                            <a wire:prevent href="{{ Storage::url($documentFile->file_attachment) }}" type="button"
                                class="btn d-inline-flex align-items-center gap-2 d-inline-block custom__form__document__file__container"
                                target="_blank">
                                @switch(pathinfo(basename($documentFile->file_attachment),
                                    PATHINFO_EXTENSION))
                                    @case('pdf')
                                        <i class="bi bi-filetype-pdf file__type"></i>
                                    @break

                                    @case('doc')
                                        <i class="bi bi-filetype-doc file__type"></i>
                                    @break

                                    @case('docx')
                                        <i class="bi bi-filetype-docx file__type"></i>
                                    @break

                                    @case('xlsx')
                                        <i class="bi bi-filetype-xlsx file__type"></i>
                                    @break

                                    @case('xls')
                                        <i class="bi bi-filetype-xls file__type"></i>
                                    @break

                                    @case('csv')
                                        <i class="bi bi-filetype-csv file__type"></i>
                                    @break

                                    @default
                                @endswitch
                                {{ basename($documentFile->file_attachment) }}
                            </a>
                            <div class="dropdown-center custom__form__document__action__buttons__container">
                                <button type="button"
                                    class="btn btn-sm d-flex align-items-center justify-content-center btn__dropdown__menu__custom__form__document"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu border-0 {{ $isDeleting && $deleteDocumentFileId === $documentFile->id ? 'show' : '' }}"
                                    style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(-67.3333px, 27px, 0px);">
                                    @if ($isDeleting && $deleteDocumentFileId === $documentFile->id)
                                        <div class="d-flex flex-column">
                                            <small class="text-center mt-1">Delete file?</small>
                                            <div class="d-flex align-items-center gap-1">
                                                <button wire:click="deleteCustomFormFile({{ $documentFile->id }})"
                                                    type="button"
                                                    class="btn dropdown-item d-flex align-items-center justify-content-center text-danger"
                                                    style="background-color: #eaecf3;">
                                                    Delete
                                                </button>
                                                <button wire:click="cancelDeleteCustomFile({{ $documentFile->id }})"
                                                    type="button"
                                                    class="btn dropdown-item d-flex align-items-center justify-content-center">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <li>
                                            <button wire:click="deleteFileConfirm({{ $documentFile->id }})"
                                                type="button"
                                                class="btn dropdown-item d-flex align-items-center gap-2 text-danger">
                                                <i class="bi bi-trash"></i>
                                                Delete
                                            </button>
                                        </li>
                                        <li>
                                            <a wire:prevent
                                                class="dropdown-item d-flex align-items-center gap-2 text-dark"
                                                href="{{ Storage::url($documentFile->file_attachment) }}"
                                                download="">
                                                <i class="bi bi-download"></i>
                                                Download
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
