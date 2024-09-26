<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Enums\FieldEnableOptionEnum;
use App\Enums\FieldRequiredOptionEnum;
use App\Enums\FieldTypesEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\Field;
use App\Models\FieldHeaderValue;
use App\Models\Form;
use App\Models\HelpTopic;
use App\Models\Ticket;
use Exception;
use Livewire\Component;
use Spatie\LaravelOptions\Options;

class HelpTopicList extends Component
{
    use BasicModelQueries;

    public ?Form $helpTopicForm = null;
    public ?int $deleteHelpTopicId = null;
    public ?int $deleteHelpTopicFormId = null;
    public ?string $deleteHelpTopicFormName = null;

    public ?string $selectedHelpTopicName = null;
    public ?int $selectedFormId = null;
    public ?string $selectedFormName = null;
    public ?string $selectedFormFieldName = null;
    public ?string $selectedFormVariableName = null;
    public ?string $selectedFormFieldType = null;
    public ?int $selectedFormFieldColumnNumber = null;
    public bool $selectedFormFieldIsRequired = false;
    public bool $selectedFormFieldIsEnabled = false;
    public bool $selectedFormFieldAsHeaderField = false;
    public bool $selectedFormFieldIsForTicketNumber = false;
    public array $selectedFormAddedFields = [];

    public ?int $editSelectedFieldFormId = null;
    public ?int $editSelectedFieldId = null;
    public ?string $editSelectedFieldName = null;
    public ?string $editSelectedFieldType = null;
    public ?string $editSelectedFieldAssignedColumnNumber = null;
    public bool $editSelectedFieldRequired = false;
    public bool $editSelectedFieldEnabled = false;
    public bool $editSelectedFieldIsHeaderField = false;
    public bool $editSelectedFieldIsForTicketNumber = false;
    public bool $editSelectedFieldIsCurrentlyEditing = false;
    public bool $editSelectedFieldIsAssociatedWithTicketNumber = false;
    public ?string $deleteSelectedFormFieldName = null;
    public ?int $deleteSelectedFormFieldId = null;

    public ?int $editFormId = null;
    public ?string $editFormName = null;
    public bool $editFormNameCurrentlyEditing = false;
    public ?int $editAddedFieldId = null;
    public ?string $editAddedFieldName = null;
    public ?string $editAddedFieldType = null;
    public ?string $editAddedFieldVariableName = null;
    public ?int $editAddedFieldAssignedColumn = null;
    public bool $editAddedFieldIsHeaderField = false;
    public bool $editAddedFieldIsForTicketNumber = false;
    public bool $editAddedFieldRequired = false;
    public bool $editAddedFieldEnabled = false;
    public bool $editAddedFieldIsCurrentlyEditing = false;

    protected $listeners = ['loadHelpTopics' => '$refresh'];

    public function deleteHelpTopic(HelpTopic $helpTopic)
    {
        $this->deleteHelpTopicId = $helpTopic->id;
        $this->selectedHelpTopicName = $helpTopic->name;
        $this->dispatchBrowserEvent('show-delete-help-topic-modal');
    }

    public function delete()
    {
        try {
            $helpTopic = HelpTopic::find($this->deleteHelpTopicId);
            if ($helpTopic) {
                $helpTopic->delete();
                $this->deleteHelpTopicId = null;
                $this->dispatchBrowserEvent('close-modal');
                noty()->addSuccess('Help topic successfully deleted');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function viewHelpTopicForm(HelpTopic $helpTopic)
    {
        $this->cancelEditFormName();
        $this->editSelectedFieldIsCurrentlyEditing = false;
        $this->helpTopicForm = $helpTopic->form;
    }

    public function deleteHelpTopicFormConfirm(Form $form)
    {
        $this->deleteHelpTopicFormId = $form->id;
        $this->deleteHelpTopicFormName = $form->name;
    }

    public function cancelDeleteForHelpTopicForm()
    {
        $this->reset(['deleteHelpTopicFormId', 'deleteHelpTopicFormName']);
        $this->dispatchBrowserEvent('close-delete-confirmation-of-helptopic-form');
    }

    public function deleteHelpTopicForm()
    {
        try {
            $helpTopicForm = Form::findOrFail($this->deleteHelpTopicFormId);
            if ($helpTopicForm) {
                $helpTopicForm->delete();
                $this->deleteHelpTopicFormId = null;
                $this->emitSelf('loadHelpTopics');
                $this->reset('deleteHelpTopicFormId', 'deleteHelpTopicFormName');
                $this->dispatchBrowserEvent('close-delete-confirmation-of-helptopic-form');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function editFormName(Form $form)
    {
        $this->editSelectedFieldIsCurrentlyEditing = false;
        $this->resetEditProperties();

        $this->editFormNameCurrentlyEditing = true;
        $this->editFormId = $form->id;
        $this->editFormName = $form->name;
    }

    public function updateFormName()
    {
        Form::where('id', $this->editFormId)
            ->update(['name' => $this->editFormName]);
        $this->cancelEditFormName();
        $this->emitSelf('loadHelpTopics');
    }

    public function discardEditFormName(Form $form)
    {
        if ($form->id == $this->editFormId) {
            $this->cancelEditFormName();
        }
    }

    public function editSelectedField(Field $field, Form $form)
    {
        $this->cancelEditFormName();

        $this->editSelectedFieldIsCurrentlyEditing = true;
        $this->editSelectedFieldFormId = $form->id;
        $this->editSelectedFieldId = $field->id;
        $this->editSelectedFieldName = $field->name;
        $this->editSelectedFieldType = $field->type;
        $this->editSelectedFieldIsHeaderField = $field->is_header_field;
        $this->editSelectedFieldAssignedColumnNumber = $field->assigned_column;
        $this->editSelectedFieldIsForTicketNumber = $field->is_for_ticket_number;
        $this->editSelectedFieldRequired = $field->is_required;
        $this->editSelectedFieldEnabled = $field->is_enabled;
        $this->editSelectedFieldIsAssociatedWithTicketNumber = $field->is_for_ticket_number;

        $this->resetValidation();

        $this->dispatchBrowserEvent('event-edit-select-field', [
            'editCurrentSelectedFieldColumnNumber' => $this->editSelectedFieldAssignedColumnNumber,
            'editCurrentSelectedFieldType' => $this->editSelectedFieldType,
        ]);
    }

    public function cancelEditFormName()
    {
        $this->editFormId = null;
        $this->editFormName = null;
        $this->editFormNameCurrentlyEditing = false;
    }

    public function cancelEditSelectedFormField()
    {
        $this->editSelectedFieldIsCurrentlyEditing = false;
        $this->resetEditProperties();
    }

    public function deleteSelectedFormField(Field $field)
    {
        $this->deleteSelectedFormFieldId = $field->id;
        $this->deleteSelectedFormFieldName = $field->name;
    }

    public function cancelDeleteSelectedFormField()
    {
        $this->deleteSelectedFormFieldId = null;
        $this->deleteSelectedFormFieldName = null;
    }

    public function deleteFormField()
    {
        try {
            $field = Field::findOrFail($this->deleteSelectedFormFieldId);
            $field->delete();
            $this->emitSelf('loadHelpTopics');
            $this->dispatchBrowserEvent('close-delete-selected-form-field-modal');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function addFieldToSelectedForm(Form $form)
    {
        $this->cancelEditFormName();
        $this->selectedFormId = $form->id;
        $this->selectedFormName = $form->name;
    }

    public function updateSelectedFormField()
    {
        if (!$this->editSelectedFieldName) {
            $this->addError('editSelectedFieldName', 'Field name is required');
            return;
        }

        if (!$this->editSelectedFieldType) {
            $this->addError('editSelectedFieldType', 'Field type is required');
            return;
        }

        if (
            $this->editSelectedFieldIsForTicketNumber &&
            Field::where([
                ['id', '!=', $this->editSelectedFieldId],
                ['is_for_ticket_number', true]
            ])->exists()
        ) {
            noty()->addError('Field associated with ticket number already exists');
            return;
        }

        Field::where([
            ['id', $this->editSelectedFieldId],
            ['form_id', $this->editSelectedFieldFormId],
        ])
            ->update([
                'name' => $this->editSelectedFieldName,
                'label' => $this->editSelectedFieldName,
                'type' => $this->editSelectedFieldType,
                'variable_name' => $this->convertToVariable($this->editSelectedFieldName),
                'is_required' => $this->editSelectedFieldRequired,
                'is_enabled' => $this->editSelectedFieldEnabled,
                'assigned_column' => $this->editSelectedFieldAssignedColumnNumber ?? null,
                'is_header_field' => $this->editSelectedFieldIsHeaderField,
                'is_for_ticket_number' => $this->editSelectedFieldIsForTicketNumber
            ]);

        $this->editSelectedFieldIsCurrentlyEditing = false;
        $this->resetEditProperties();
        $this->resetValidation();
        $this->emitSelf('loadHelpTopics');
    }

    public function cancelAddFieldToSelectedForm()
    {
        $this->actionOnEditFormAddedField();
        $this->cancelEditSelectedFormField();
        $this->selectedFormFieldName = null;
        $this->selectedFormFieldType = null;
        $this->selectedFormFieldColumnNumber = null;
        $this->selectedFormFieldIsRequired = false;
        $this->selectedFormFieldIsEnabled = false;
        $this->selectedFormFieldAsHeaderField = false;
        $this->selectedFormFieldIsForTicketNumber = false;
    }

    public function saveSelectedFormAddedFields()
    {
        $fieldNameExists = Field::where([
            ['form_id', $this->selectedFormId],
            ['name', $this->selectedFormFieldName],
        ])->exists();

        if ($fieldNameExists) {
            $this->addError('selectedFormFieldName', 'Field name already exists on this form');
            return;
        }

        if (!$this->selectedFormFieldName) {
            $this->addError('selectedFormFieldName', 'Field name is required');
            return;
        }

        if (!$this->selectedFormFieldType) {
            $this->addError('selectedFormFieldType', 'Field type is required');
            return;
        }

        array_push(
            $this->selectedFormAddedFields,
            [
                'name' => $this->selectedFormFieldName,
                'label' => $this->selectedFormFieldName,
                'type' => $this->selectedFormFieldType,
                'variable_name' => $this->selectedFormVariableName,
                'is_required' => $this->selectedFormFieldIsRequired,
                'is_enabled' => $this->selectedFormFieldIsEnabled,
                'assigned_column' => $this->selectedFormFieldColumnNumber,
                'is_header_field' => $this->selectedFormFieldAsHeaderField,
                'is_for_ticket_number' => $this->selectedFormFieldIsForTicketNumber
            ]
        );

        $this->reset([
            'selectedFormFieldName',
            'selectedFormFieldType',
            'selectedFormVariableName',
            'selectedFormFieldIsRequired',
            'selectedFormFieldIsEnabled',
            'selectedFormFieldColumnNumber',
            'selectedFormFieldAsHeaderField',
            'selectedFormFieldIsForTicketNumber'
        ]);

        $this->resetValidation();
        $this->dispatchBrowserEvent('selected-form-clear-form-fields');
        $this->emitSelf('loadHelpTopics');
    }

    public function editSelectedFormAddedField(int $fieldKey)
    {
        try {
            $this->editAddedFieldId = $fieldKey;

            foreach ($this->selectedFormAddedFields as $key => $field) {
                if ($this->editAddedFieldId === $key) {
                    $this->editAddedFieldName = $field['name'];
                    $this->editAddedFieldType = $field['type'];
                    $this->editAddedFieldVariableName = $field['variable_name'];
                    $this->editAddedFieldRequired = $field['is_required'] == FieldRequiredOptionEnum::YES->value;
                    $this->editAddedFieldEnabled = $field['is_enabled'] == FieldEnableOptionEnum::YES->value;
                    $this->editAddedFieldAssignedColumn = $field['assigned_column'];
                    $this->editAddedFieldIsHeaderField = $field['is_header_field'];
                    $this->editAddedFieldIsForTicketNumber = $field['is_for_ticket_number'];
                }

                $this->dispatchBrowserEvent('edit-selected-form-added-field-show-select-field', [
                    'editAddedFieldType' => $this->editAddedFieldType,
                    'editAddedFieldAssignedColumn' => $this->editAddedFieldAssignedColumn,
                    'editAddedFieldIsHeaderField' => $this->editAddedFieldIsHeaderField
                ]);
            }
            $this->resetValidation();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updateSelectedFormAddedField(int $fieldKey)
    {
        try {
            if (!$this->editAddedFieldName) {
                $this->addError('editAddedFieldName', 'Field name is required');
                return;
            }

            if (!$this->editAddedFieldType) {
                $this->addError('editAddedFieldType', 'Field type is required');
                return;
            }

            foreach ($this->selectedFormAddedFields as $key => &$field) {
                if ($this->editAddedFieldId === $fieldKey && $key === $fieldKey) {
                    $field['name'] = $this->editAddedFieldName;
                    $field['type'] = $this->editAddedFieldType;
                    $field['variable_name'] = $this->editAddedFieldVariableName;
                    $field['is_required'] = $this->editAddedFieldRequired;
                    $field['is_enabled'] = $this->editAddedFieldEnabled;
                    $field['assigned_column'] = $this->editAddedFieldAssignedColumn;
                    $field['is_header_field'] = $this->editAddedFieldIsHeaderField;
                    $field['is_for_ticket_number'] = $this->editAddedFieldIsForTicketNumber;
                }
            }

            $this->editSelectedFormAddedFieldAction();
            $this->emitSelf('loadHelpTopics');
            $this->resetValidation();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updatedEditAddedFieldIsHeaderField($value)
    {
        if ($value) {
            $this->dispatchBrowserEvent('enable-edit-assign-column');
        } else {
            $this->editAddedFieldIsForTicketNumber = false;
            $this->editAddedFieldAssignedColumn = null;
            $this->dispatchBrowserEvent('disable-edit-assign-column');
        }
    }

    public function cancelEditSelectedFormAddedFieldAction(int $fieldKey)
    {
        if ($this->editAddedFieldId === $fieldKey) {
            $this->editSelectedFormAddedFieldAction();
        }
    }

    public function editSelectedFormAddedFieldAction()
    {
        $this->editAddedFieldId = null;
        $this->editAddedFieldName = null;
        $this->editAddedFieldType = null;
        $this->editAddedFieldRequired = false;
        $this->editAddedFieldEnabled = false;
        $this->editAddedFieldVariableName = null;
    }

    public function removeSelectedFormAddedField(int $fieldKey)
    {
        $this->selectedFormAddedFields = array_filter(
            $this->selectedFormAddedFields,
            fn($key) => $key !== $fieldKey,
            ARRAY_FILTER_USE_KEY
        );
    }

    public function selectedFormSaveAddedField()
    {
        try {
            if (empty($this->selectedFormAddedFields) && $this->selectedFormFieldName && $this->selectedFormFieldType) {
                session()->flash('selected_form_added_fields_error', 'Please add the fields first');
                return;
            }

            if (empty($this->selectedFormAddedFields)) {
                session()->flash('selected_form_added_fields_error', 'Form fields are required');
                return;
            }

            $selectedForm = Form::find($this->selectedFormId);
            foreach ($this->selectedFormAddedFields as $field) {
                $selectedForm->fields()->create([
                    'name' => $field['name'],
                    'label' => $field['name'],
                    'type' => $field['type'],
                    'variable_name' => $field['variable_name'],
                    'is_required' => $field['is_required'] == FieldRequiredOptionEnum::YES->value,
                    'is_enabled' => $field['is_enabled'] == FieldEnableOptionEnum::YES->value,
                    'assigned_column' => $field['assigned_column'],
                    'is_header_field' => $field['is_header_field'],
                    'is_for_ticket_number' => $field['is_for_ticket_number']
                ]);
            }

            $this->actionOnEditFormAddedField();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function actionOnEditFormAddedField()
    {
        $this->editSelectedFormAddedFieldAction();
        $this->selectedFormAddedFields = [];
        $this->emitSelf('loadHelpTopics');
        $this->dispatchBrowserEvent('close-selected-form-add-field');
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('loadHelpTopics');
        $this->dispatchBrowserEvent('selected-form-clear-form-fields');
    }

    public function resetEditProperties()
    {
        $this->editSelectedFieldFormId = null;
        $this->editSelectedFieldId = null;
        $this->editSelectedFieldName = null;
        $this->editSelectedFieldType = null;
        $this->editSelectedFieldRequired = false;
        $this->editSelectedFieldEnabled = false;
        $this->editSelectedFieldAssignedColumnNumber = null;
        $this->editSelectedFieldIsHeaderField = false;
        $this->editSelectedFieldIsForTicketNumber = false;
    }

    public function convertToVariable($variable)
    {
        return preg_replace('/[^a-zA-Z0-9_.]+/', '_', strtolower(trim($variable)));
    }

    public function updatedSelectedFormFieldName($value)
    {
        $this->selectedFormVariableName = $this->convertToVariable($value);
    }

    public function hasAssociatedTicketFieldExists()
    {
        return Field::where('is_for_ticket_number', true)->exists();
    }

    public function hasAssociatedTicketField()
    {
        return $this->selectedFormFieldAsHeaderField
            && Field::where('is_for_ticket_number', true)->exists();
    }

    public function updatedSelectedFormFieldAsHeaderField($value)
    {
        if ($value) {
            $this->dispatchBrowserEvent('add-selected-form-show-select-column-number');
        } else {
            $this->selectedFormFieldColumnNumber = null;
            $this->selectedFormFieldAsHeaderField = false;
        }
    }

    public function render()
    {
        return view('livewire.staff.help-topic.help-topic-list', [
            'helpTopics' => $this->queryHelpTopics(),
            'addFormFieldFieldTypes' => Options::forEnum(FieldTypesEnum::class)->toArray(),
        ]);
    }
}
