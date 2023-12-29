<div>
    <div class="row my-4">
        <form wire:submit.prevent="saveCustomField">
            <h6 class="px-1">Add field</h6>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-0">
                                <div class="d-flex align-items-center text-start px-1 td__content">
                                    <input wire:model="name" class="form-control form__field" type="text"
                                        id="fieldName" placeholder="Enter field name">
                                </div>
                                @error('name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </td>
                            <td class="px-0">
                                <div class="d-flex align-items-center text-start px-1 td__content">
                                    <div class="w-100">
                                        <div id="select-field-type" wire:ignore></div>
                                    </div>
                                </div>
                                @error('type')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </td>
                            <td class="px-0">
                                <div class="d-flex align-items-center text-start px-1 td__content">
                                    <div class="w-100">
                                        <div id="select-required-field" wire:ignore></div>
                                    </div>
                                </div>
                                @error('is_required')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </td>
                            <td class="px-0">
                                <div class="d-flex align-items-center text-start px-1 td__content">
                                    <input wire:model="variable_name" class="form-control form__field" type="text"
                                        placeholder="Variable name here" disabled>
                                </div>
                                @error('variable_name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                <div class="d-flex align-items-center gap-2">
                    <button type="submit"
                        class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send">
                        <span wire:loading wire:target="saveCustomField" class="spinner-border spinner-border-sm"
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

        window.addEventListener('clear-form', () => {
            selectFieldType.reset();
            selectRequired.reset();
        });
    </script>
@endpush
