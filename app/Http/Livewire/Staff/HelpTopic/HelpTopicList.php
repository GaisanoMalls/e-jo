<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Enums\FieldEnableOptionEnum;
use App\Enums\FieldRequiredOptionEnum;
use App\Enums\FieldTypesEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\Field;
use App\Models\Form;
use App\Models\HelpTopic;
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
    public bool $selectedFormFieldIsRequired = false;
    public bool $selectedFormFieldIsEnabled = false;
    public array $selectedFormAddedFields = [];

    public ?int $editSelectedFieldFormId = null;
    public ?int $editSelectedFieldId = null;
    public ?string $editSelectedFieldName = null;
    public ?string $editSelectedFieldType = null;
    public bool $editSelectedFieldRequired = false;
    public bool $editSelectedFieldEnabled = false;
    public bool $editSelectedFieldIsCurrentlyEditing = false;

    public ?int $editFormId = null;
    public ?string $editFormName = null;
    public bool $editFormNameCurrentlyEditing = false;
    public ?int $editAddedFieldId = null;
    public ?string $editAddedFieldName = null;
    public ?string $editAddedFieldType = null;
    public ?string $editAddedFieldVariableName = null;
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
        Form::where('id', $this->editFormId)->update(['name' => $this->editFormName]);
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
        $this->editSelectedFieldRequired = $field->is_required;
        $this->editSelectedFieldEnabled = $field->is_enabled;
        $this->resetValidation();

        $this->dispatchBrowserEvent('event-edit-selected-field-type', [
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

    public function deleteFormField(Field $field)
    {
        try {
            $field->delete();
            $this->emitSelf('loadHelpTopics');
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
                'is_enabled' => $this->editSelectedFieldEnabled
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
        $this->selectedFormFieldIsRequired = false;
        $this->selectedFormFieldIsEnabled = false;
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
                'is_enabled' => $this->selectedFormFieldIsEnabled
            ]
        );

        $this->reset('selectedFormFieldName', 'selectedFormFieldType', 'selectedFormVariableName', 'selectedFormFieldIsRequired', 'selectedFormFieldIsEnabled');
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
                }

                $this->dispatchBrowserEvent('edit-selected-form-added-field-show-select-field', [
                    'editAddedFieldType' => $this->editAddedFieldType,
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
                }
            }
            $this->editSelectedFormAddedFieldAction();
            $this->emitSelf('loadHelpTopics');
            $this->resetValidation();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
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
                    'is_enabled' => $field['is_enabled'] == FieldEnableOptionEnum::YES->value
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
    }

    public function convertToVariable($value)
    {
        return preg_replace('/[^a-zA-Z0-9_.]+/', '_', strtolower(trim($value)));
    }

    public function updatedSelectedFormFieldName($value)
    {
        $this->selectedFormVariableName = $this->convertToVariable($value);
    }

    public function render()
    {
        return view('livewire.staff.help-topic.help-topic-list', [
            'helpTopics' => $this->queryHelpTopics(),
            'addFormFieldFieldTypes' => Options::forEnum(FieldTypesEnum::class)->toArray(),
        ]);
    }
}
