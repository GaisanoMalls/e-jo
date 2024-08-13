<div>
    <div class="table-responsive custom__table">
        @if ($helpTopics->isNotEmpty())
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Help Topic</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Service Department</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Team</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">SLA</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Form</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($helpTopics as $helpTopic)
                        <tr wire:key="help-topic-{{ $helpTopic->id }}">
                            <td>
                                <div
                                    class="d-flex gap-4 justify-content-between align-items-center text-start td__content">
                                    <span>{{ $helpTopic->name }}</span>
                                    {{-- @if ($helpTopic->specialProject?->amount)
                                        <div class="d-flex align-items-center rounded-4"
                                            style="background-color: #f1f3ef; padding: 0.1rem 0.4rem;">
                                            <span style="font-size: 11px; color: #D32839;">₱</span>
                                            <span style="font-size: 11px; color: #D32839;">
                                                {{ number_format($helpTopic->specialProject?->amount, 2) }}
                                            </span>
                                        </div>
                                    @endif --}}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->serviceDepartment?->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->team?->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->sla?->time_unit }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1 text-start td__content">
                                    @if ($helpTopic->form)
                                        <span wire:click="viewHelpTopicForm({{ $helpTopic->id }})"
                                            data-bs-toggle="modal" data-bs-target="#viewFormModal"
                                            class="btn__view__form">
                                            View
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->dateCreated() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->dateUpdated() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                    <button data-tooltip="Edit" data-tooltip-position="top"
                                        data-tooltip-font-size="11px"
                                        onclick="window.location.href='{{ route('staff.manage.help_topic.edit_details', $helpTopic->id) }}'"
                                        type="button" class="btn action__button">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm action__button mt-0" data-bs-toggle="modal"
                                        data-bs-target="#deleteHelpTopicModal"
                                        wire:click="deleteHelpTopic({{ $helpTopic->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                <small style="font-size: 14px;">No records for help topics.</small>
            </div>
        @endif
    </div>

    {{-- Delete Help Topic Modal --}}
    <div wire:ignore.self class="modal fade modal__confirm__delete__help__topic" id="deleteHelpTopicModal"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="delete">
                    <div class="modal-body border-0 text-center pt-4 pb-1">
                        <h6 class="fw-bold mb-4"
                            style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                            Confirm Delete
                        </h6>
                        <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                            Are you sure you want to delete this help topic?
                        </p>
                        <strong>{{ $selectedHelpTopicName }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                        <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit"
                            class="btn d-flex align-items-center justify-content-center gap-2 w-50 btn__confirm__delete btn__confirm__modal"
                            wire:click="delete">
                            <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Yes, delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete help topic form --}}
    <div wire:ignore.self class="modal fade modal__confirm__delete__help__topic" id="deleteHelpTopicFormModal"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="delete">
                    <div class="modal-body border-0 text-center pt-4 pb-1">
                        <h6 class="fw-bold mb-4"
                            style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                            Confirm Delete
                        </h6>
                        <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                            Delete help topic form?
                        </p>
                        <strong>{{ $deleteHelpTopicFormName }}</strong>
                    </div>
                    <hr>
                    <div wire:click="cancelDeleteForHelpTopicForm"
                        class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                        <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button"
                            class="btn d-flex align-items-center justify-content-center gap-2 w-50 btn__confirm__delete btn__confirm__modal"
                            wire:click="deleteHelpTopicForm">
                            <span wire:loading wire:target="deleteHelpTopicForm"
                                class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span>
                            Yes, delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- View help topic form --}}
    <div wire:ignore.self class="modal fade help__topic__modal" id="viewFormModal" tabindex="-1"
        aria-labelledby="viewFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0 mb-3">
                    <h1 class="modal-title modal__title" id="addNewHelpTopicModalLabel">
                        Help Topic Form
                    </h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                @if ($helpTopicForm)
                    <div wire:key="form-{{ $helpTopicForm->id }}"
                        class="d-flex flex-column gap-2 py-3 helptopic__form__list">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3 helptopic__form__name__header">
                                <div class="d-flex align-items-center gap-2"
                                    style="font-size: 0.95rem; color: black;">
                                    @if ($editFormNameCurrentlyEditing && $editFormId == $helpTopicForm->id)
                                        <input wire:model="editFormName"
                                            class="form-control border-0 edit__form__name__field" type="text"
                                            placeholder="Enter form name">
                                    @else
                                        <i class="bi bi-journal-text"></i>
                                        {{ $helpTopicForm->name }}
                                    @endif
                                </div>
                                @if ($editFormNameCurrentlyEditing && $editFormId == $helpTopicForm->id)
                                    <div class="d-flex align-items-center gap-3">
                                        <button wire:click="updateFormName" type="button"
                                            class="btn btn-sm d-flex border-0 align-items-center justify-content-center"
                                            style="height: 4px; width: 4px;">
                                            <i wire:loading.remove
                                                wire:target="editFormName({{ $helpTopicForm->id }})"
                                                class="bi bi-check-lg"></i>
                                            <i wire:loading wire:target="editFormName({{ $helpTopicForm->id }})"
                                                class='bx bx-loader bx-spin'></i>
                                        </button>
                                        <button wire:click="discardEditFormName({{ $helpTopicForm->id }})"
                                            type="button"
                                            class="btn btn-sm d-flex border-0 align-items-center justify-content-center"
                                            style="height: 4px; width: 4px;">
                                            <i wire:loading.remove
                                                wire:target="discardEditFormName({{ $helpTopicForm->id }})"
                                                class="bi bi-x" style="font-size: 16px;"></i>
                                            <i wire:loading
                                                wire:target="discardEditFormName({{ $helpTopicForm->id }})"
                                                class='bx bx-loader bx-spin'></i>
                                        </button>
                                    </div>
                                @else
                                    <button wire:click="editFormName({{ $helpTopicForm->id }})" type="button"
                                        class="btn btn-sm d-flex border-0 align-items-center justify-content-center d-none btn__edit__form__name"
                                        style="height: 4px; width: 4px;">
                                        <i wire:loading.remove wire:target="editFormName({{ $helpTopicForm->id }})"
                                            class="bi bi-pencil" style="font-size: 11px;"></i>
                                        <i wire:loading wire:target="editFormName({{ $helpTopicForm->id }})"
                                            class='bx bx-loader bx-spin'></i>
                                    </button>
                                @endif
                            </div>
                            <div wire:click="addFieldToSelectedForm({{ $helpTopicForm->id }})"
                                class="d-flex align-items-center gap-1">
                                <button data-bs-target="#addFieldForSelectedForm" data-bs-toggle="modal"
                                    class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                                <button wire:click="deleteHelpTopicFormConfirm({{ $helpTopicForm }})"
                                    class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                    data-bs-toggle="modal" data-bs-target="#deleteHelpTopicFormModal">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        @if ($helpTopicForm->fields)
                            <div class="d-flex flex-wrap align-items-center gap-1">
                                @foreach ($helpTopicForm->fields as $field)
                                    <span wire:key="field-{{ $field->id }}"
                                        class="d-flex align-items-center gap-2 form__fields__container"
                                        style="font-size: 0.65rem; border: 1px solid #dddddd; border-radius: 20px; padding: 3px 7px 3px 8px; {{ $field->is_enabled ? 'color: black;' : 'color: #6b7280;' }} {{ $editSelectedFieldIsCurrentlyEditing && $editSelectedFieldId === $field->id ? 'border: 0.08rem solid #d32839; font-weight: 500; color: #d32839;' : '' }}">
                                        {{ $field->name }}
                                        @if (!$editSelectedFieldIsCurrentlyEditing || $editSelectedFieldId !== $field->id)
                                            <div class="d-flex align-items-center gap-1 d-none field__container">
                                                <button
                                                    wire:click="editSelectedField({{ $field->id }}, {{ $helpTopicForm->id }})"
                                                    type="button"
                                                    class="btn btn-sm d-flex border-0 align-items-center justify-content-center"
                                                    style="height: 4px; width: 4px;">
                                                    <i wire:loading.remove
                                                        wire:target="editSelectedField({{ $field->id }}, {{ $helpTopicForm->id }})"
                                                        class="bi bi-pencil" style="font-size: 9px;"></i>
                                                    <i wire:loading
                                                        wire:target="editSelectedField({{ $field->id }}, {{ $helpTopicForm->id }})"
                                                        class='bx bx-loader bx-spin'></i>
                                                </button>
                                                <button wire:click="deleteFormField({{ $field->id }})"
                                                    type="button"
                                                    class="btn btn-sm d-flex border-0 align-items-center justify-content-center"
                                                    style="height: 4px; width: 4px;">
                                                    <i wire:loading.remove
                                                        wire:target="deleteFormField({{ $field->id }})"
                                                        class="bi bi-x"></i>
                                                    <i wire:loading="deleteFormField({{ $field->id }})"
                                                        wire:target="deleteFormField({{ $field->id }})"
                                                        class="bx bx-loader bx-spin"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                            @if ($editSelectedFieldIsCurrentlyEditing && $editSelectedFieldFormId === $helpTopicForm->id)
                                <div class="row mt-1 px-3 py-4 rounded-3"
                                    style="background-color: #f3f4f6; margin-left: 2px; margin-right: 2px; border: 0.08rem solid #dddddd;">
                                    <div
                                        class="col-lg-3 col-md-6 d-flex flex-column justify-content-end position-relative">
                                        <div class="mb-3">
                                            <label for="fieldName" class="form-label text-muted form__field__label"
                                                style="font-weight: 500;">
                                                Field name
                                            </label>
                                            <input wire:model="editSelectedFieldName" class="form-control form__field"
                                                type="text" id="fieldName" placeholder="Enter field name">
                                            @error('editSelectedFieldName')
                                                <span class="error__message position-absolute"
                                                    style="bottom: -3px !important;">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-3 col-md-6 d-flex flex-column justify-content-end position-relative">
                                        <div class="mb-3">
                                            <label class="form-label text-muted form__field__label"
                                                style="font-weight: 500;">Type</label>
                                            <div class="w-100">
                                                <div id="edit-selected-field-type" wire:ignore>
                                                </div>
                                            </div>
                                            @error('editSelectedFieldType')
                                                <span class="error__message position-absolute"
                                                    style="bottom: -3px !important;">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-3 col-md-6 d-flex flex-column justify-content-end position-relative">
                                        <div class="mb-3">
                                            <label class="form-label text-muted form__field__label"
                                                style="font-weight: 500;">Required</label>
                                            <div class="w-100">
                                                <div id="edit-selected-field-required" wire:ignore>
                                                </div>
                                            </div>
                                        </div>
                                        @error('editSelectedFieldRequired')
                                            <span class="error__message position-absolute"
                                                style="bottom: -3px !important;">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div
                                        class="col-lg-3 col-md-6 d-flex flex-column justify-content-end position-relative">
                                        <div class="mb-3">
                                            <label class="form-label text-muted form__field__label"
                                                style="font-weight: 500;">Enabled</label>
                                            <div class="w-100">
                                                <div id="edit-selected-field-enabled" wire:ignore>
                                                </div>
                                            </div>
                                        </div>
                                        @error('editSelectedFieldEnabled')
                                            <span class="error__message position-absolute"
                                                style="bottom: -3px !important;">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <button wire:click="updateSelectedFormField" type="button"
                                            class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send"
                                            style="padding: 0.6rem 1rem;
                                                border-radius: 0.563rem;
                                                font-size: 0.875rem;
                                                background-color: #d32839;
                                                color: white;
                                                font-weight: 500;
                                                box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem 0.25rem -0.0625rem rgba(20, 20, 20, 0.07);">
                                            <span wire:loading wire:target="updateSelectedFormField"
                                                class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true">
                                            </span>
                                            Update
                                        </button>
                                        <button wire:click="cancelEditSelectedFormField" type="button"
                                            class="btn m-0 btn__modal__footer btn__cancel"
                                            style="adding: 0.6rem 1rem;
                                                    border-radius: 0.563rem;
                                                    font-size: 0.875rem;
                                                    border: 1px solid #e7e9eb;
                                                    background-color: transparent;
                                                    color: #d32839;
                                                    font-weight: 500;">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @else
                            <em style="font-size: 0.75rem; color: #848d96;">
                                Empty fields
                            </em>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add field to the selected form --}}
    <div wire:ignore.self class="modal fade help__topic__modal" id="addFieldForSelectedForm" tabindex="-1"
        aria-labelledby="addFieldForSelectedFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0 mb-3">
                    <h1 class="modal-title modal__title" id="addNewHelpTopicModalLabel">
                        Add fields to {{ $selectedFormName }}
                    </h1>
                </div>
                <div class="mx-1">
                    <h6>Add field</h6>
                    @if (session()->has('selected_form_added_fields_error'))
                        <small class="fw-semibold text-danger mb-1">
                            {{ session('selected_form_added_fields_error') }}
                        </small>
                    @endif
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-6 d-flex flex-column justify-content-end position-relative">
                            <div class="mb-2">
                                <label for="fieldName" class="form-label text-muted form__field__label"
                                    style="font-weight: 500;">
                                    Field name
                                    <em style="font-size: 0.75rem;">(No special characters)</em>
                                </label>
                                <div class="d-flex align-items-center text-start px-0 td__content">
                                    <input wire:model="selectedFormFieldName" class="form-control form__field"
                                        type="text" id="fieldName" placeholder="Enter field name">
                                </div>
                                @error('selectedFormFieldName')
                                    <span class="error__message position-absolute" style="bottom: -5px !important;">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 d-flex flex-column justify-content-end position-relative">
                            <div class="mb-2">
                                <label class="form-label text-muted form__field__label"
                                    style="font-weight: 500;">Type</label>
                                <div class="d-flex align-items-center text-start px-0 td__content">
                                    <div class="w-100">
                                        <div id="add-selected-form-field-select-field-type" wire:ignore></div>
                                    </div>
                                </div>
                                @error('selectedFormFieldType')
                                    <span class="error__message position-absolute" style="bottom: -5px !important;">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 d-flex flex-column justify-content-end position-relative">
                            <div class="mb-2">
                                <label class="form-label text-muted form__field__label"
                                    style="font-weight: 500;">Required</label>
                                <div class="d-flex align-items-center text-start px-0 td__content">
                                    <div class="w-100">
                                        <div id="add-selected-form-field-select-required-field" wire:ignore></div>
                                    </div>
                                </div>
                            </div>
                            @error('selectedFormFieldIsRequired')
                                <span class="error__message position-absolute" style="bottom: -24px !important;">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-6 d-flex flex-column justify-content-end position-relative">
                            <div class="mb-3">
                                <label class="form-label text-muted form__field__label"
                                    style="font-weight: 500;">Enabled</label>
                                <div class="w-100">
                                    <div id="add-selected-form-field-select-enabled-field" wire:ignore>
                                    </div>
                                </div>
                            </div>
                            @error('selectedFormFieldIsEnabled')
                                <span class="error__message position-absolute" style="bottom: -3px !important;">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="col-12 mt-3 col-md-6 d-flex flex-column justify-content-end">
                            <div class="mb-2">
                                <button wire:click="saveSelectedFormAddedFields" type="button"
                                    class="btn btn-sm d-flex gap-2 ms-1 align-items-center justify-content-center outline-none px-3 rounded-3"
                                    style="height: 45px; background-color: #edeef0; border: 1px solid #e7e9eb; margin-bottom: 10px;">
                                    <span wire:loading.remove wire:target="saveSelectedFormAddedFields">
                                        <i class="bi bi-save"></i>
                                    </span>
                                    <div wire:loading wire:target="saveSelectedFormAddedFields"
                                        class="spinner-border spinner-border-sm loading__spinner" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    Add field
                                </button>
                            </div>
                        </div>
                    </div>
                    @if (!empty($selectedFormAddedFields))
                        <div class="row my-4 px-3">
                            <div class="table-responsive custom__table">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="border-0 table__head__label px-2">Name</th>
                                            <th class="border-0 table__head__label px-2">Type</th>
                                            <th class="border-0 table__head__label px-2">Required</th>
                                            <th class="border-0 table__head__label px-2">Enable</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($selectedFormAddedFields as $key => $field)
                                            <tr wire:key="added-field-{{ $key }}">
                                                <td>
                                                    <div class="d-flex align-items-center text-start px-0 td__content"
                                                        style="height: 0; min-width: 200px;">
                                                        @if ($editAddedFieldId === $key)
                                                            <input wire:model="editAddedFieldName"
                                                                class="form-control form__field" type="text"
                                                                placeholder="Enter field name">
                                                        @else
                                                            <span>{{ $field['name'] }}</span>
                                                        @endif
                                                    </div>
                                                    @error('editAddedFieldName')
                                                        <span class="error__message">
                                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center text-start px-0 td__content"
                                                        style="height: 0;">
                                                        @if ($editAddedFieldId === $key)
                                                            <div class="w-100">
                                                                <div id="edit-added-select-field-type" wire:ignore>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span>{{ $field['type'] }}</span>
                                                        @endif
                                                    </div>
                                                    @error('editAddedFieldType')
                                                        <span class="error__message">
                                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center text-start px-0 td__content"
                                                        style="height: 0;">
                                                        @if ($editAddedFieldId === $key)
                                                            <div class="w-100">
                                                                <div id="edit-added-select-field-required" wire:ignore>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span>{{ $field['is_required'] ? 'Yes' : 'No' }}</span>
                                                        @endif
                                                    </div>
                                                    @error('editAddedFieldRequired')
                                                        <span class="error__message">
                                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center text-start px-0 td__content"
                                                        style="height: 0; min-width: 200px;">
                                                        @if ($editAddedFieldId === $key)
                                                            <div class="w-100">
                                                                <div id="edit-added-select-field-enabled" wire:ignore>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span>{{ $field['is_enabled'] ? 'Yes' : 'No' }}</span>
                                                        @endif
                                                    </div>
                                                    @error('editAddedFieldEnabled')
                                                        <span class="error__message">
                                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </td>
                                                <td class="px-0">
                                                    <div
                                                        class="d-flex align-items-center gap-2 justify-content-end px-2">
                                                        @if ($editAddedFieldId === $key)
                                                            <button
                                                                class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                                                wire:click="updateSelectedFormAddedField({{ $key }})">
                                                                <i class="bi bi-check-lg"
                                                                    style="font-size: 18px;"></i>
                                                            </button>
                                                            <button
                                                                class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                                                wire:click="cancelEditSelectedFormAddedFieldAction({{ $key }})">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        @else
                                                            <button
                                                                class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                                                wire:click="editSelectedFormAddedField({{ $key }})">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button
                                                                class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                                                wire:click="removeSelectedFormAddedField({{ $key }})">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer modal__footer p-0 mt-3 justify-content-between border-0 gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button wire:click="selectedFormSaveAddedField" type="button"
                            class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send">
                            <span wire:loading wire:target="selectedFormSaveAddedField"
                                class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span>
                            Save field
                        </button>
                        <button wire:click="cancelAddFieldToSelectedForm" type="button"
                            class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-target="#viewFormModal" data-bs-toggle="modal">
                            Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#deleteHelpTopicModal').modal('hide');
        });

        window.addEventListener('show-delete-help-topic-modal', () => {
            $('#deleteHelpTopicModal').modal('show');
        });

        const addSelectedFormFieldSelectFieldType = document.querySelector('#add-selected-form-field-select-field-type');
        const addSelectedFormFieldSelectRequired = document.querySelector('#add-selected-form-field-select-required-field');
        const addSelectedFormFieldSelectEnabled = document.querySelector('#add-selected-form-field-select-enabled-field');

        const addFormFieldFieldTypeOption = @json($addFormFieldFieldTypes).map(addFormFieldFieldType => ({
            label: addFormFieldFieldType.label,
            value: addFormFieldFieldType.value
        }));

        const addFormFieldSelectRequiredOption = @json($addFormFieldRequiredOption).map(addFormFieldRequired => ({
            label: addFormFieldRequired.label,
            value: addFormFieldRequired.value
        }));

        const addFormFieldSelectEnableOption = @json($addFormFieldEnableOption).map(addFormFieldEnable => ({
            label: addFormFieldEnable.label,
            value: addFormFieldEnable.value
        }));

        VirtualSelect.init({
            ele: addSelectedFormFieldSelectFieldType,
            options: addFormFieldFieldTypeOption
        });

        VirtualSelect.init({
            ele: addSelectedFormFieldSelectRequired,
            options: addFormFieldSelectRequiredOption
        });

        VirtualSelect.init({
            ele: addSelectedFormFieldSelectEnabled,
            options: addFormFieldSelectEnableOption
        });

        addSelectedFormFieldSelectFieldType.addEventListener('change', (event) => {
            @this.set('selectedFormFieldType', event.target.value)
        });

        addSelectedFormFieldSelectRequired.addEventListener('change', (event) => {
            @this.set('selectedFormFieldIsRequired', event.target.value)
        });

        addSelectedFormFieldSelectEnabled.addEventListener('change', (event) => {
            @this.set('selectedFormFieldIsEnabled', event.target.value);
        });

        window.addEventListener('selected-form-clear-form-fields', () => {
            addSelectedFormFieldSelectFieldType.reset();
            addSelectedFormFieldSelectRequired.reset();
            addSelectedFormFieldSelectEnabled.reset();
        });

        // Edit seleted field
        window.addEventListener('event-edit-selected-field-type', (event) => {
            const editSelectedFieldType = document.querySelector('#edit-selected-field-type');
            const editSelectedFieldRequired = document.querySelector('#edit-selected-field-required');
            const editSelectedFieldEnabled = document.querySelector('#edit-selected-field-enabled');

            const editCurrentSelectedFieldType = event.detail.editCurrentSelectedFieldType;
            const editCurrentSelectedFieldRequired = event.detail.editCurrentSelectedFieldRequired;
            const editCurrentSelectedFieldEnabled = event.detail.editCurrentSelectedFieldEnabled;

            VirtualSelect.init({
                ele: editSelectedFieldType,
                options: addFormFieldFieldTypeOption,
            });

            VirtualSelect.init({
                ele: editSelectedFieldRequired,
                options: addFormFieldSelectRequiredOption,
            });

            VirtualSelect.init({
                ele: editSelectedFieldEnabled,
                options: addFormFieldSelectEnableOption,
            });

            editSelectedFieldType.reset();
            editSelectedFieldRequired.reset();
            editSelectedFieldEnabled.reset();

            editSelectedFieldType.setValue(editCurrentSelectedFieldType);
            editSelectedFieldRequired.setValue(editCurrentSelectedFieldRequired);
            editSelectedFieldEnabled.setValue(editCurrentSelectedFieldEnabled);

            editSelectedFieldType.addEventListener('change', (event) => {
                @this.set('editSelectedFieldType', event.target.value);
            });

            editSelectedFieldRequired.addEventListener('change', (event) => {
                @this.set('editSelectedFieldRequired', event.target.value);
            });

            editSelectedFieldEnabled.addEventListener('change', (event) => {
                @this.set('editSelectedFieldEnabled', event.target.value);
            });
        });

        // Edit selected form (added fields)
        window.addEventListener('edit-selected-form-added-field-show-select-field', (event) => {
            const editAddedFieldType = event.detail.editAddedFieldType;
            const editAddedFieldRequired = event.detail.editAddedFieldRequired;
            const editAddedFieldEnabled = event.detail.editAddedFieldEnabled;

            const editAddedSelectFieldType = document.querySelector('#edit-added-select-field-type');
            const editAddedSelectFieldRequired = document.querySelector('#edit-added-select-field-required');
            const editAddedSelectFieldEnabled = document.querySelector('#edit-added-select-field-enabled');

            if (editAddedSelectFieldType && editAddedSelectFieldRequired && editAddedSelectFieldEnabled) {
                VirtualSelect.init({
                    ele: editAddedSelectFieldType,
                    options: addFormFieldFieldTypeOption,
                    search: true,
                    popupDropboxBreakpoint: '3000px'
                });

                VirtualSelect.init({
                    ele: editAddedSelectFieldRequired,
                    options: addFormFieldSelectRequiredOption,
                    popupDropboxBreakpoint: '3000px'
                });

                VirtualSelect.init({
                    ele: editAddedSelectFieldEnabled,
                    options: addFormFieldSelectEnableOption,
                    popupDropboxBreakpoint: '3000px'
                });

                editAddedSelectFieldType.reset();
                editAddedSelectFieldRequired.reset();
                editAddedSelectFieldEnabled.reset();

                editAddedSelectFieldType.setValue(editAddedFieldType);
                editAddedSelectFieldRequired.setValue(editAddedFieldRequired ? 'Yes' : 'No');
                editAddedSelectFieldEnabled.setValue(editAddedFieldEnabled ? 'Yes' : 'No');

                editAddedSelectFieldType.addEventListener('change', (event) => {
                    @this.set('editAddedFieldType', event.target.value);
                });

                editAddedSelectFieldRequired.addEventListener('change', (event) => {
                    @this.set('editAddedFieldRequired', event.target.value);
                });

                editAddedSelectFieldEnabled.addEventListener('change', (event) => {
                    @this.set('editAddedFieldEnabled', event.target.value);
                });
            }
        });

        window.addEventListener('close-selected-form-add-field', () => {
            $('#viewFormModal').modal('show');
            $('#addFieldForSelectedForm').modal('hide');

            addSelectedFormFieldSelectFieldType.reset();
            addSelectedFormFieldSelectRequired.reset();
            addSelectedFormFieldSelectEnabled.reset();

            editAddedSelectFieldType.reset();
            editAddedSelectFieldRequired.reset();
            editAddedSelectFieldEnabled.reset();
        });

        window.addEventListener('close-delete-confirmation-of-helptopic-form', () => {
            $('#viewFormModal').modal('show');
            $('#deleteHelpTopicFormModal').modal('hide');
        });
    </script>
@endpush
