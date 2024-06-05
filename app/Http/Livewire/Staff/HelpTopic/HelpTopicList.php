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
use App\Models\Role;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;
use Spatie\LaravelOptions\Options;
use stdClass;

class HelpTopicList extends Component
{
    use BasicModelQueries;

    public ?Collection $helpTopicForms = null;
    public $deleteHelpTopicId;
    public $deleteHelpTopicFormId;
    public $deleteHelpTopicFormName;

    public $selectedHelpTopicName;
    public $selectedFormId;
    public $selectedFormName;
    public $selectedFormFieldName;
    public $selectedFormVariableName;
    public $selectedFormFieldType;
    public $selectedFormFieldIsRequired;
    public $selectedFormFieldIsEnabled;
    public $selectedFormAddedFields = [];

    public $editSelectedFieldFormId;
    public $editSelectedFieldId;
    public $editSelectedFieldName;
    public $editSelectedFieldType;
    public $editSelectedFieldRequired;
    public $editSelectedFieldEnabled;
    public $editSelectedFieldIsCurrentlyEditing = false;

    public $editFormId;
    public $editFormName;
    public $editFormNameCurrentlyEditing = false;

    public $editAddedFieldId;
    public $editAddedFieldName;
    public $editAddedFieldType;
    public $editAddedFieldRequired;
    public $editAddedFieldEnabled;
    public $editAddedFieldVariableName;
    public $editAddedFieldIsCurrentlyEditing = false;

    protected $listeners = ['loadHelpTopics' => '$refresh'];

    public function mount()
    {
        $this->helpTopicForms = collect([]);
    }

    public function deleteHelpTopic(HelpTopic $helpTopic)
    {
        $this->deleteHelpTopicId = $helpTopic->id;
        $this->selectedHelpTopicName = $helpTopic->name;
        $this->dispatchBrowserEvent('show-delete-help-topic-modal');
    }

    public function deleteForm()
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
        $this->helpTopicForms = $helpTopic->forms()->get(['id', 'name', 'visible_to']);
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
            $helpTopicForm = Form::find($this->deleteHelpTopicFormId);
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
            'editCurrentSelectedFieldRequired' => $this->editSelectedFieldRequired ? 'Yes' : 'No',
            'editCurrentSelectedFieldEnabled' => $this->editSelectedFieldEnabled ? 'Yes' : 'No',
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

        if (!$this->editSelectedFieldRequired) {
            $this->addError('editSelectedFieldRequired', 'This field is required');
            return;
        }

        if (!$this->editSelectedFieldEnabled) {
            $this->addError('editSelectedFieldEnabled', 'This field is required');
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
                'is_required' => $this->editSelectedFieldRequired == FieldRequiredOptionEnum::YES->value,
                'is_enabled' => $this->editSelectedFieldEnabled == FieldEnableOptionEnum::YES->value
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
        $this->selectedFormFieldIsRequired = null;
        $this->selectedFormFieldIsEnabled = null;
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

        if (!$this->selectedFormFieldIsRequired) {
            $this->addError('selectedFormFieldIsRequired', 'This field is required');
            return;
        }

        if (!$this->selectedFormFieldIsEnabled) {
            $this->addError('selectedFormFieldIsEnabled', 'This field is required');
            return;
        }

        array_push(
            $this->selectedFormAddedFields,
            [
                'name' => $this->selectedFormFieldName,
                'label' => $this->selectedFormFieldName,
                'type' => $this->selectedFormFieldType,
                'variable_name' => $this->selectedFormVariableName,
                'is_required' => $this->selectedFormFieldIsRequired == FieldRequiredOptionEnum::YES->value,
                'is_enabled' => $this->selectedFormFieldIsEnabled == FieldEnableOptionEnum::YES->value
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
                    'editAddedFieldRequired' => $this->editAddedFieldRequired == FieldRequiredOptionEnum::YES->value,
                    'editAddedFieldEnabled' => $this->editAddedFieldEnabled == FieldEnableOptionEnum::YES->value
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

            if (!$this->editAddedFieldRequired) {
                $this->addError('editAddedFieldRequired', 'This field is required');
                return;
            }

            if (!$this->editAddedFieldEnabled) {
                $this->addError('editAddedFieldEnabled', 'This field is required');
                return;
            }

            foreach ($this->selectedFormAddedFields as $key => &$field) {
                if ($this->editAddedFieldId === $fieldKey && $key === $fieldKey) {
                    $field['name'] = $this->editAddedFieldName;
                    $field['type'] = $this->editAddedFieldType;
                    $field['variable_name'] = $this->editAddedFieldVariableName;
                    $field['is_required'] = $this->editAddedFieldRequired == FieldRequiredOptionEnum::YES->value;
                    $field['is_enabled'] = $this->editAddedFieldEnabled == FieldEnableOptionEnum::YES->value;
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
        foreach (array_keys($this->selectedFormAddedFields) as $key) {
            if ($fieldKey === $key) {
                unset($this->selectedFormAddedFields[$key]);
            }
        }
    }

    public function selectedFormSaveAddedField()
    {
        try {

            if (empty($this->selectedFormAddedFields) && $this->selectedFormFieldName && $this->selectedFormFieldType && $this->selectedFormFieldIsRequired && $this->selectedFormFieldIsEnabled) {
                session()->flash('selected_form_required_form_fields_error', 'Please the fields first');
                return;
            }

            if (empty($this->selectedFormAddedFields)) {
                session()->flash('selected_form_required_form_fields_error', 'Form fields are required');
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
        $this->editSelectedFieldRequired = null;
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
            'addFormFieldEnableOption' => Options::forEnum(FieldEnableOptionEnum::class)->toArray(),
            'addFormFieldUserRoles' => Options::forModels(Role::class)->toArray(),
            'addFormFieldRequiredOption' => Options::forEnum(FieldRequiredOptionEnum::class)->toArray(),
        ]);
    }
}
