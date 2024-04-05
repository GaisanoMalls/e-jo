<div>
    <div class="row mb-4">
        <form wire:submit.prevent="saveForm">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-2">
                        <label for="formName" class="form-label form__field__label">Form name</label>
                        <input type="text" wire:model="form_name" class="form-control form__field" id="formName"
                            placeholder="Enter help topic name">
                        @error('form_name')
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
                        @error('help_topic')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <h6 class="px-1">Add field</h6>
            @if (session()->has('required_form_fields_error'))
                <small class="fw-semibold mb-1 text-danger ms-1">{{ session('required_form_fields_error') }}</small>
            @endif
            <div class="custom__table">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 table__head__label px-1">Name</th>
                            <th class="border-0 table__head__label px-1">Type</th>
                            <th class="border-0 table__head__label px-1">Required</th>
                            <th class="border-0 table__head__label px-1">
                                Variable Name
                            </th>
                            <th class="border-0 table__head__label px-1">
                                Save
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-0">
                                <div class="d-flex align-items-center text-start px-1 td__content">
                                    <input wire:model="name" class="form-control form__field" type="text"
                                        id="fieldName" placeholder="Enter field name">
                                </div>
                                @if (session()->has('field_name_error'))
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ session('field_name_error') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-0">
                                <div class="d-flex align-items-center text-start px-1 td__content">
                                    <div class="w-100">
                                        <div id="select-field-type" wire:ignore></div>
                                    </div>
                                </div>
                                @if (session()->has('field_type_error'))
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ session('field_type_error') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-0">
                                <div class="d-flex align-items-center text-start px-1 td__content">
                                    <div class="w-100">
                                        <div id="select-required-field" wire:ignore></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-0">
                                <div class="d-flex align-items-center text-start px-1 td__content">
                                    <input wire:model="variable_name" class="form-control form__field" type="text"
                                        placeholder="Variable name here" disabled>
                                </div>
                            </td>
                            <td class="px-0">
                                <button wire:click="addField" type="button"
                                    class="btn btn-sm d-flex align-items-center justify-content-center outline-none rounded-3"
                                    style="height: 45px; width: 45px; background-color: #edeef0; border: 1px solid #e7e9eb;">
                                    <span wire:loading.remove wire:target="addField">
                                        <i class="bi bi-save"></i>
                                    </span>
                                    <div wire:loading wire:target="addField"
                                        class="spinner-border spinner-border-sm loading__spinner" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @if (!empty($addedFields))
                <div class="row my-4 px-3">
                    <div class="custom__table">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="border-0 table__head__label px-2">Enable</th>
                                    <th class="border-0 table__head__label px-2">Name</th>
                                    <th class="border-0 table__head__label px-2">Type</th>
                                    <th class="border-0 table__head__label px-2">Required</th>
                                    <th class="border-0 table__head__label px-2">
                                        Variable Name
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($addedFields as $key => $field)
                                    <tr wire:key="field-{{ $key }}">
                                        <td>
                                            <div class="form-check">
                                                <input value="{{ $key }}" class="form-check-input"
                                                    type="checkbox" role="switch" wire:loading.attr="disabled")
                                                    style="margin-top: 3px;">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start px-0 td__content"
                                                style="height: 0;">
                                                <span>{{ $field['name'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start px-0 td__content"
                                                style="height: 0;">
                                                <span>{{ $field['type'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start px-0 td__content"
                                                style="height: 0;">
                                                <span>{{ $field['is_required'] ? 'Yes' : 'No' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start px-0 td__content"
                                                style="height: 0;">
                                                <span>{{ $field['variable_name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-0">
                                            <div class="d-flex align-items-center justify-content-end px-2">
                                                <button
                                                    class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                                    wire:click="removeField({{ $key }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            <div class="modal-footer modal__footer p-0 mt-3 justify-content-between border-0 gap-2">
                <div class="d-flex align-items-center gap-2">
                    <button type="submit"
                        class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send">
                        <span wire:loading wire:target="saveForm" class="spinner-border spinner-border-sm"
                            role="status" aria-hidden="true">
                        </span>
                        Save field
                    </button>
                    <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                        data-bs-dismiss="modal" wire:click="cancel">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('livewire-select')
    <script>
        const selectFieldType = document.querySelector('#select-field-type');
        const selectRequired = document.querySelector('#select-required-field');
        const selectHelpTopic = document.querySelector('#select-help-topic');

        const fieldTypeOption = [
            @foreach ($fieldTypes as $fieldType)
                {
                    label: "{{ $fieldType['label'] }}",
                    value: "{{ $fieldType['value'] }}"
                },
            @endforeach
        ];

        VirtualSelect.init({
            ele: selectFieldType,
            options: fieldTypeOption,
            search: true,
        });

        selectFieldType.addEventListener('change', () => {
            @this.set('type', selectFieldType.value);
        });

        const selectRequiredOption = [
            @foreach ($fieldRequiredOption as $fieldRequired)
                {
                    label: "{{ $fieldRequired['label'] }}",
                    value: "{{ $fieldRequired['value'] }}"
                },
            @endforeach
        ];

        VirtualSelect.init({
            ele: selectRequired,
            options: selectRequiredOption,
        });

        selectRequired.addEventListener('change', () => {
            @this.set('is_required', selectRequired.value);
        });

        const selectHelpTopicOption = [
            @foreach ($helpTopics as $helpTopic)
                {
                    label: "{{ $helpTopic->name }}",
                    value: "{{ $helpTopic->id }}"
                },
            @endforeach
        ];

        VirtualSelect.init({
            ele: selectHelpTopic,
            options: selectHelpTopicOption,
            search: true,
        });

        selectHelpTopic.addEventListener('change', () => {
            @this.set('help_topic', parseInt(selectHelpTopic.value));
        });

        window.addEventListener('clear-form', () => {
            selectFieldType.reset();
            selectRequired.reset();
            selectHelpTopic.reset();
        });

        window.addEventListener('clear-form-fields', () => {
            selectFieldType.reset();
            selectRequired.reset();
        });
    </script>
@endpush
