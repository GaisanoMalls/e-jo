@php
    use App\Models\Role;
@endphp

<div>
    <div class="row mb-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-2">
                    <label for="formName" class="form-label form__field__label">Form name</label>
                    <input type="text" wire:model="formName" class="form-control form__field" id="formName"
                        placeholder="Enter help topic name">
                    @error('formName')
                        <span class="error__message">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="sla" class="form-label form__field__label">
                        Help topic
                    </label>
                    <div>
                        <div id="select-help-topic" wire:ignore></div>
                    </div>
                    @error('helpTopic')
                        <span class="error__message">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="sla" class="form-label form__field__label">
                        Visible to
                    </label>
                    <div>
                        <div id="select-form-visibility" wire:ignore></div>
                    </div>
                    @error('visibleTo')
                        <span class="error__message">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="sla" class="form-label form__field__label">
                        Editable to
                    </label>
                    <div>
                        <div id="select-form-editable" wire:ignore></div>
                    </div>
                    @error('visibleTo')
                        <span class="error__message">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mx-1">
            <h6>Add field</h6>
            @if (session()->has('required_form_fields_error'))
                <small class="fw-semibold text-danger mb-1">{{ session('required_form_fields_error') }}</small>
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
                            <input wire:model="name" class="form-control form__field" type="text" id="fieldName"
                                placeholder="Enter field name">
                        </div>
                        @error('name')
                            <span class="error__message position-absolute" style="bottom: -5px !important;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 d-flex flex-column justify-content-end position-relative">
                    <div class="mb-2">
                        <label class="form-label text-muted form__field__label" style="font-weight: 500;">Type</label>
                        <div class="d-flex align-items-center text-start px-0 td__content">
                            <div class="w-100">
                                <div id="select-field-type" wire:ignore></div>
                            </div>
                        </div>
                        @error('type')
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
                                <div id="select-required-field" wire:ignore></div>
                            </div>
                        </div>
                    </div>
                    @error('is_required')
                        <span class="error__message position-absolute" style="bottom: -24px !important;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="col-lg-3 col-md-6 d-flex flex-column justify-content-end position-relative">
                    <div class="mb-2">
                        <label class="form-label text-muted form__field__label" style="font-weight: 500;">Enable</label>
                        <div class="d-flex align-items-center text-start px-0 td__content">
                            <div class="w-100">
                                <div id="select-enable-field" wire:ignore></div>
                            </div>
                        </div>
                    </div>
                    @error('is_enabled')
                        <span class="error__message position-absolute" style="bottom: -24px !important;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="col-12 mt-3 col-md-6 d-flex flex-column justify-content-end">
                    <div class="mb-2">
                        <button wire:click="addField" type="button"
                            class="btn btn-sm d-flex gap-2 ms-1 align-items-center justify-content-center outline-none px-3 rounded-3"
                            style="height: 45px; background-color: #edeef0; border: 1px solid #e7e9eb; margin-bottom: 10px;">
                            <span wire:loading.remove wire:target="addField">
                                <i class="bi bi-save"></i>
                            </span>
                            <div wire:loading wire:target="addField"
                                class="spinner-border spinner-border-sm loading__spinner" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Add field
                        </button>
                    </div>
                </div>
            </div>
            @if (!empty($addedFields))
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
                                @foreach ($addedFields as $key => $field)
                                    <tr wire:key="field-{{ $key }}">
                                        <td>
                                            <div class="d-flex align-items-center text-start px-0 td__content"
                                                style="height: 0; min-width: 200px;">
                                                @if ($editingFieldId === $key)
                                                    <input wire:model="editingFieldName"
                                                        class="form-control form__field" type="text"
                                                        placeholder="Enter field name">
                                                @else
                                                    <span>{{ $field['name'] }}</span>
                                                @endif
                                            </div>
                                            @error('editingFieldName')
                                                <span class="error__message position-absolute"
                                                    style="bottom: -3px !important;">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start px-0 td__content"
                                                style="height: 0;">
                                                @if ($editingFieldId === $key)
                                                    <div class="w-100">
                                                        <div id="editing-select-field-type" wire:ignore></div>
                                                    </div>
                                                @else
                                                    <span>{{ $field['type'] }}</span>
                                                @endif
                                            </div>
                                            @error('editingFieldType')
                                                <span class="error__message position-absolute"
                                                    style="bottom: -3px !important;">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start px-0 td__content"
                                                style="height: 0;">
                                                @if ($editingFieldId === $key)
                                                    <div class="w-100">
                                                        <div id="editing-select-field-is-required" wire:ignore></div>
                                                    </div>
                                                @else
                                                    <span>{{ $field['is_required'] ? 'Yes' : 'No' }}</span>
                                                @endif
                                            </div>
                                            @error('editingFieldRequired')
                                                <span class="error__message position-absolute"
                                                    style="bottom: -3px !important;">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start px-0 td__content"
                                                style="height: 0; min-width: 200px;">
                                                @if ($editingFieldId === $key)
                                                    <div class="w-100">
                                                        <div id="editing-select-field-enable" wire:ignore></div>
                                                    </div>
                                                @else
                                                    <span>{{ $field['is_enabled'] ? 'Yes' : 'No' }}</span>
                                                @endif
                                            </div>
                                            @error('editingFieldEnable')
                                                <span class="error__message position-absolute"
                                                    style="bottom: -3px !important;">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </td>
                                        <td class="px-0">
                                            <div class="d-flex align-items-center gap-2 justify-content-end px-2">
                                                @if ($editingFieldId === $key)
                                                    <button
                                                        class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                                        wire:click="updateAddedField({{ $key }})">
                                                        <i class="bi bi-check-lg" style="font-size: 18px;"></i>
                                                    </button>
                                                    <button
                                                        class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                                        wire:click="cancelEditAddedField({{ $key }})">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                @else
                                                    <button
                                                        class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                                        wire:click="toggleEditAddedField({{ $key }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button
                                                        class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                                        wire:click="removeField({{ $key }})">
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
        <div class="modal-footer modal__footer p-0 mt-3 mx-2 justify-content-between border-0 gap-2">
            <div class="d-flex align-items-center gap-2">
                <button wire:click="saveForm" type="button"
                    class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send">
                    <span wire:loading wire:target="saveForm" class="spinner-border spinner-border-sm" role="status"
                        aria-hidden="true">
                    </span>
                    Save form
                </button>
                <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                    data-bs-dismiss="modal" wire:click="cancel">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@push('livewire-modal')
    <script>
        const selectHelpTopic = document.querySelector('#select-help-topic');
        const selectFormVisibility = document.querySelector('#select-form-visibility');
        const selectFormEditable = document.querySelector('#select-form-editable');
        const selectFieldType = document.querySelector('#select-field-type');
        const selectRequired = document.querySelector('#select-required-field');
        const selectEnable = document.querySelector('#select-enable-field');

        const fieldTypeOption = @json($fieldTypes).map(fieldType => ({
            label: fieldType.label,
            value: fieldType.value
        }));

        VirtualSelect.init({
            ele: selectFieldType,
            options: fieldTypeOption,
            search: true,
        });

        selectFieldType.addEventListener('change', (event) => {
            @this.set('type', event.target.value);
        });

        const selectRequiredOption = @json($fieldRequiredOption).map(fieldRequired => ({
            label: fieldRequired.label,
            value: fieldRequired.value
        }));

        VirtualSelect.init({
            ele: selectRequired,
            options: selectRequiredOption,
        });

        selectRequired.addEventListener('change', (event) => {
            @this.set('is_required', event.target.value);
        });

        const selectEnableOption = @json($fieldEnableOption).map(fieldEnable => ({
            label: fieldEnable.label,
            value: fieldEnable.value
        }));

        VirtualSelect.init({
            ele: selectEnable,
            options: selectEnableOption,
        });

        selectEnable.addEventListener('change', (event) => {
            @this.set('is_enabled', event.target.value);
        });

        const selectHelpTopicOption = @json($helpTopics).map(helpTopic => ({
            label: helpTopic.name,
            value: helpTopic.id
        }));

        VirtualSelect.init({
            ele: selectHelpTopic,
            options: selectHelpTopicOption,
            search: true,
        });

        selectHelpTopic.addEventListener('change', (event) => {
            @this.set('helpTopic', parseInt(event.target.value));
        });

        selectHelpTopic.addEventListener('reset', () => {
            @this.set('helpTopic', null);
        });

        const selectFormVisibilityOption = @json($userRoles).map(role => ({
            label: role.label,
            value: role.label
        }));

        VirtualSelect.init({
            ele: selectFormVisibility,
            options: selectFormVisibilityOption,
            search: true,
            multiple: true,
            showValueAsTags: true,
        });

        selectFormVisibility.addEventListener('change', (event) => {
            @this.set('visibleTo', event.target.value)
        });

        const selectFormEditableOption = @json($userRoles).map(role => ({
            label: role.label,
            value: role.label
        }));

        VirtualSelect.init({
            ele: selectFormEditable,
            options: selectFormEditableOption,
            search: true,
            multiple: true,
            showValueAsTags: true,
        });

        selectFormEditable.addEventListener('change', (event) => {
            @this.set('editableTo', event.target.value)
        });

        window.addEventListener('clear-form', () => {
            selectFieldType.reset();
            selectRequired.reset();
            selectEnable.reset();
            selectHelpTopic.reset();
            selectFormVisibility.reset();
            selectFormEditable.reset();
        });

        window.addEventListener('clear-form-fields', () => {
            selectFieldType.reset();
            selectRequired.reset();
            selectEnable.reset();
        });

        window.addEventListener('edit-added-field-show-select-field', (event) => {
            const currentFieldType = event.detail.currentFieldType
            const currentFieldRequired = event.detail.currentFieldRequired;
            const currentFieldEnable = event.detail.currentFieldEnable;

            const editingSelectFieldType = document.querySelector('#editing-select-field-type');
            const editingSelectFieldIsRequired = document.querySelector('#editing-select-field-is-required');
            const editingSelectFieldEnable = document.querySelector('#editing-select-field-enable');

            VirtualSelect.init({
                ele: editingSelectFieldType,
                options: fieldTypeOption,
                search: true,
                popupDropboxBreakpoint: '3000px'
            });

            VirtualSelect.init({
                ele: editingSelectFieldIsRequired,
                options: selectRequiredOption,
                popupDropboxBreakpoint: '3000px'
            });

            VirtualSelect.init({
                ele: editingSelectFieldEnable,
                options: selectEnableOption,
                popupDropboxBreakpoint: '3000px'
            });

            // Reset the select field first before assigning a new value.
            editingSelectFieldType.reset();
            editingSelectFieldIsRequired.reset();
            editingSelectFieldEnable.reset();

            editingSelectFieldType.setValue(currentFieldType);
            editingSelectFieldIsRequired.setValue(currentFieldRequired ? 'Yes' : 'No');
            editingSelectFieldEnable.setValue(currentFieldEnable ? 'Yes' : 'No');

            editingSelectFieldType.addEventListener('change', (event) => {
                @this.set('editingFieldType', event.target.value);
            });

            editingSelectFieldIsRequired.addEventListener('change', (event) => {
                @this.set('editingFieldRequired', event.target.value);
            });

            editingSelectFieldEnable.addEventListener('change', (event) => {
                @this.set('editingFieldEnable', event.target.value);
            });
        });
    </script>
@endpush
