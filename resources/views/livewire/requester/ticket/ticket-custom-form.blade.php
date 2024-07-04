@php
    use App\Enums\FieldTypesEnum as FieldType;
@endphp

<div>
    <button class="btn btn-sm btn__purchase__request" data-bs-toggle="modal" data-bs-target="#ticketCustomFormModal">
        View Purchase Request
    </button>
    <div wire:ignore.self class="modal fade create__ticket__modal" id="ticketCustomFormModal" tabindex="-1"
        aria-labelledby="ticketCustomFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-lg">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="">
                    <h1 class="modal-title modal__title fs-5 px-3">{{ $ticket->helpTopic->form->name }}</h1>
                    <div class="modal-body modal__body">
                        <div class="row">
                            @if ($customFormFields->isNotEmpty())
                                @foreach ($customFormFields as $key => $field)
                                    {{-- Display those fields that are set to enabled. --}}
                                    @if ($field['is_enabled'])
                                        {{-- short text field --}}
                                        @if ($field['type'] === FieldType::SHORT_ANSWER->value)
                                            <div class="col-md-6 mb-3">
                                                <label for="field-{{ $key }}"
                                                    class="form-label input__field__label">
                                                    {{ Str::title($field['label']) }}
                                                </label>
                                                <input wire:model="customFormFields.{{ $key }}.value"
                                                    type="text" id="field-{{ $key }}"
                                                    class="form-control input__field"
                                                    placeholder="Enter {{ Str::lower($field['label']) }}"
                                                    @required($field['is_required'])>
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
                                                <label for="field-{{ $key }}"
                                                    class="form-label input__field__label">
                                                    {{ Str::title($field['label']) }}
                                                </label>
                                                <textarea wire:model="customFormFields.{{ $key }}.value" id="field-{{ $key }}"
                                                    class="form-control input__field" placeholder="Enter {{ Str::lower($field['label']) }}" @required($field['is_required'])>
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
                                                <label for="field-{{ $key }}"
                                                    class="form-label input__field__label">
                                                    {{ Str::title($field['label']) }}
                                                </label>
                                                <input wire:model="customFormFields.{{ $key }}.value"
                                                    id="field-{{ $key }}" type="number"
                                                    class="form-control input__field"
                                                    placeholder="Enter {{ Str::lower($field['label']) }}"
                                                    @required($field['is_required'])>
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
                                                <label for="field-{{ $key }}"
                                                    class="form-label input__field__label">
                                                    {{ Str::title($field['label']) }}
                                                </label>
                                                <input wire:model="customFormFields.{{ $key }}.value"
                                                    id="field-{{ $key }}" type="date"
                                                    class="form-control input__field"
                                                    placeholder="Enter {{ Str::lower($field['label']) }}"
                                                    @required($field['is_required'])>
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
                                                <label for="field-{{ $key }}"
                                                    class="form-label input__field__label">
                                                    {{ Str::title($field['label']) }}
                                                </label>
                                                <input wire:model="customFormFields.{{ $key }}.value"
                                                    id="field-{{ $key }}" type="time"
                                                    class="form-control input__field"
                                                    placeholder="Enter {{ Str::lower($field['label']) }}"
                                                    @required($field['is_required'])>
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
                                                <label for="field-{{ $key }}"
                                                    class="form-label input__field__label">
                                                    {{ Str::title($field['label']) }}
                                                </label>
                                                <input wire:model="customFormFields.{{ $key }}.value"
                                                    id="field-{{ $key }}" type="number" step=".01"
                                                    class="form-control input__field"
                                                    placeholder="Enter {{ Str::lower($field['label']) }}">
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
                                        @if ($field['type'] === FieldType::FILE->value)
                                            <div class="col-md-4 mb-3">
                                                <label for="field-{{ $key }}"
                                                    class="form-label input__field__label">
                                                    {{ Str::title($field['label']) }}
                                                </label>
                                                <div x-data="{ isUploadingCustomFormFile: false, progress: 1 }"
                                                    x-on:livewire-upload-start="isUploadingCustomFormFile = true; progress = 1"
                                                    x-on:livewire-upload-finish="isUploadingCustomFormFile = false"
                                                    x-on:livewire-upload-error="isUploadingCustomFormFile = false"
                                                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                                                    <input wire:model="customFormFields.{{ $key }}.value"
                                                        id="field-{{ $key }}" type="file"
                                                        class="form-control form-control-sm border-0 ticket__file"
                                                        placeholder="Enter {{ Str::lower($field['label']) }}"
                                                        accept=".xlsx,.xls,image/*,.doc,.docx,.pdf,.csv" multiple>
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
                                                        <span class="d-flex align-items-center gap-1"
                                                            style="font-size: 12px;">
                                                            <i x-show="isUploadingCustomFormFile"
                                                                class='bx bx-loader-circle bx-spin'
                                                                style="font-size: 14px;"></i>
                                                            <span
                                                                x-show="isUploadingCustomFormFile">Uploading...</span>
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
                                @endforeach
                            @endif
                        </div>
                        @if ($customFormFiles)
                            <label class="form-label input__field__label">
                                File {{ $customFormFiles->count() === 1 ? 'Attachment' : 'Attachments' }}
                                <small>
                                    ({{ $customFormFiles->count() }}
                                    {{ $customFormFiles->count() === 1 ? 'item' : 'items' }})
                                </small>
                            </label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($customFormFiles as $key => $file)
                                    <div wire:key="customFormFile-{{ $key }}"
                                        class="custom__form__image__container position-relative">
                                        <a href="{{ Storage::url($file->file_attachment) }}" target="_blank">
                                            <img src="{{ Storage::url($file->file_attachment) }}"
                                                class="custom__form__file__attachment__image">
                                        </a>
                                        <button wire:click="deleteCustomFormFile({{ $file->id }})"
                                            class="btn btn-sm d-flex align-items-center justify-content-center position-absolute btn__delete__custom__form__file">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    @switch(pathinfo(basename($file->file_attachment), PATHINFO_EXTENSION))
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
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2 p-3">
                        <button type="button" class="btn ticket__modal__button btn__close__ticket__modal"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit"
                            class="btn d-flex align-items-center justify-content-center gap-2 ticket__modal__button">
                            {{-- <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span> --}}
                            Send Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
