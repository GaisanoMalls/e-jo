<div>
    <div class="row my-4 px-3">
        @if ($fields->isNotEmpty())
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
                        @foreach ($fields as $index => $field)
                            <form wire:submit.prevent="updateCustomeField">
                                <tr wire:key="field-{{ $index + 1 }}">
                                    <td>
                                        <div class="form-check">
                                            <input wire:click="toggleField({{ $field->id }})"
                                                value="{{ $field->id }}" class="form-check-input" type="checkbox"
                                                role="switch" wire:loading.attr="disabled" @checked($field->isEnabled())
                                                style="margin-top: 3px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-start px-0 td__content"
                                            style="height: 0;">
                                            @if ($editingFieldId === $field->id)
                                                <input wire:model="name" class="form-control form__field" type="text"
                                                    id="fieldName">
                                            @else
                                                <span>{{ $field->name }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-start px-0 td__content"
                                            style="height: 0;">
                                            @if ($editingFieldId === $field->id)
                                                <div class="w-100" id="editFieldTypeContainer">
                                                    <div id="select-edit-field-type" wire:ignore></div>
                                                </div>
                                            @else
                                                <span>{{ $field->type }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-start px-0 td__content"
                                            style="height: 0;">
                                            @if ($editingFieldId === $field->id)
                                                <div class="w-100">
                                                    <div id="select-edit-required-field" wire:ignore></div>
                                                </div>
                                            @else
                                                <span>{{ $field->is_required }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-start px-0 td__content"
                                            style="height: 0;">
                                            @if ($editingFieldId === $field->id)
                                                <input wire:model="variable_name" class="form-control form__field"
                                                    type="text" placeholder="Variable name here" disabled>
                                            @else
                                                <span>{{ $field->variable_name }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-0">
                                        <div class="d-flex align-items-center justify-content-end px-2">
                                            @if ($editingFieldId === $field->id)
                                                <button type="submit" class="btn action__button">
                                                    <i class="bi bi-check-lg" style="font-size: 18px;"></i>
                                                </button>
                                            @endif
                                            <button wire:click="toggleEdit({{ $field->id }})" type="button"
                                                class="btn action__button">
                                                @if ($editingFieldId === $field->id)
                                                    <i class="bi bi-x-lg"></i>
                                                @else
                                                    <i class="bi bi-pencil"></i>
                                                @endif
                                            </button>
                                            <button
                                                class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                                wire:click="deleteField({{ $field->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </form>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-light py-3 px-4 rounded-3">
                <small style="font-size: 14px;">Empty fields</small>
            </div>
        @endif
    </div>
</div>

@push('livewire-select')
    <script>
        window.addEventListener('show-dropdown-fields', (event) => {
            const editFieldTypeOption = [
                @foreach ($editFieldTypes as $editFieldType)
                    {
                        label: "{{ $editFieldType['label'] }}",
                        value: "{{ $editFieldType['value'] }}"
                    },
                @endforeach
            ];

            const editFieldRequiredOption = [
                @foreach ($editFieldRequiredOption as $editFieldRequired)
                    {
                        label: "{{ $editFieldRequired['label'] }}",
                        value: "{{ $editFieldRequired['value'] }}"
                    },
                @endforeach
            ];

            const selectEditFieldType = document.querySelector('#select-edit-field-type');
            const selectEditRequiredField = document.querySelector('#select-edit-required-field');

            if (selectEditFieldType || selectEditRequiredField) {
                VirtualSelect.init({
                    ele: selectEditFieldType,
                    options: editFieldTypeOption,
                    search: true,
                });

                VirtualSelect.init({
                    ele: selectEditRequiredField,
                    options: editFieldRequiredOption,
                });

                selectEditFieldType.setValue(event.detail.currentFieldType);
                selectEditRequiredField.setValue(event.detail.currentRequiredField);
            }

            selectEditFieldType.addEventListener('change', () => {
                @this.set('type', selectEditFieldType.value);
            });
            selectEditRequiredField.addEventListener('change', () => {
                @this.set('is_required', selectEditRequiredField.value);
            });
        });
    </script>
@endpush
