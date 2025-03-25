<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Enums\FieldEnableOptionEnum;
use App\Enums\FieldRequiredOptionEnum;
use App\Enums\FieldTypesEnum;
use App\Enums\PredefinedFieldValueEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\Field;
use App\Models\Form;
use App\Models\HelpTopic;
use Exception;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
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
    public ?string $selectedFormFieldGetConfigValueFrom = null;
    public bool $selectedFormFieldIsRequired = false;
    public bool $selectedFormFieldIsEnabled = false;
    public bool $selectedFormFieldAsPredefinedField = false;
    public bool $selectedFormFieldAsHeaderField = false;
    public array $selectedFormAddedFields = [];

    public ?int $editSelectedFieldFormId = null;
    public ?int $editSelectedFieldId = null;
    public ?string $editSelectedFieldName = null;
    public ?string $editSelectedFieldType = null;
    public ?string $editSelectedFieldAssignedColumnNumber = null;
    public bool $editSelectedFieldRequired = false;
    public bool $editSelectedFieldEnabled = false;
    public bool $editSelectedFieldIsHeaderField = false;
    public bool $editSelectedFieldIsCurrentlyEditing = false;
    public ?string $deleteSelectedFormFieldName = null;
    private ?int $deleteSelectedFormFieldId = null;
    private ?ArrayObject $editSelectedFieldConfig = null;
    public ?string $editSelectedFieldGetConfigValueFrom = null;
    public bool $editAsPredefinedField = false;

    public ?int $editFormId = null;
    public ?string $editFormName = null;
    public bool $editFormNameCurrentlyEditing = false;

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
                noty()->addSuccess('Help topic successfully deleted.');
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
        $helpTopicForm = Form::findOrFail($this->deleteHelpTopicFormId);
        $helpTopicForm->delete();
        return redirect()->route('staff.manage.help_topic.index');
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
        $this->editSelectedFieldRequired = $field->is_required;
        $this->editSelectedFieldEnabled = $field->is_enabled;
        $this->editSelectedFieldAssignedColumnNumber = $field->assigned_column;
        $this->editSelectedFieldIsHeaderField = $field->is_header_field;
        $this->editSelectedFieldConfig = $field->config;
        $this->editAsPredefinedField = $field->config['get_value_from']['value'] !== null;

        $this->resetValidation();

        $this->dispatchBrowserEvent('edit-form-select-field', [
            'editCurrentSelectedFieldColumnNumber' => $this->editSelectedFieldAssignedColumnNumber,
            'editCurrentSelectedFieldType' => $this->editSelectedFieldType,
        ]);

        $this->dispatchBrowserEvent('show-edit-select-predefined-field', [
            'takenPredefinedFieldValues' => $this->takenPredefinedFieldValues(),
            'editPredefinedFieldValues' => PredefinedFieldValueEnum::getOptions(),
            'editCurrentPredefinedValue' => $this->editSelectedFieldConfig['get_value_from']['value'] ?? null
        ]);
    }

    public function updatedEditAsPredefinedField($value)
    {
        if ($value) {
            $this->dispatchBrowserEvent('show-edit-select-predefined-field', [
                'takenPredefinedFieldValues' => $this->takenPredefinedFieldValues(),
                'editPredefinedFieldValues' => PredefinedFieldValueEnum::getOptions(),
                'editCurrentPredefinedValue' => $this->editSelectedFieldConfig['get_value_from']['value'] ?? null
            ]);
        } else {
            $this->editSelectedFieldGetConfigValueFrom = null;
            $this->resetValidation('editSelectedFieldGetConfigValueFrom');
        }
    }

    public function updatedEditSelectedFieldIsHeaderField($value)
    {
        if ($value) {
            $this->dispatchBrowserEvent('edit-form-select-field', [
                'editCurrentSelectedFieldColumnNumber' => $this->editSelectedFieldAssignedColumnNumber,
                'editCurrentSelectedFieldType' => $this->editSelectedFieldType,
            ]);
        } else {
            $this->editSelectedFieldAssignedColumnNumber = null;
            $this->resetValidation('editSelectedFieldAssignedColumnNumber');
        }
    }

    private function takenPredefinedFieldValues()
    {
        return Field::where('form_id', $this->editSelectedFieldFormId)
            ->get()
            ->filter(function ($field) {
                $config = $field->config; // Directly access the ArrayObject
                return isset($config['get_value_from']['value']);
            })
            ->pluck('config')
            ->map(function ($config) {
                return $config['get_value_from']['value'];
            })
            ->values()
            ->toArray();
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
        if ($this->editAsPredefinedField) {
            $configFieldValue = Field::find($this->editSelectedFieldId)
                ->whereNot('id', $this->editSelectedFieldId)
                ->whereJsonContains('config->get_value_from->value', $this->editSelectedFieldGetConfigValueFrom)
                ->first();

            if (!$this->editSelectedFieldGetConfigValueFrom) {
                $this->addError('editSelectedFieldGetConfigValueFrom', 'Predefined field is required');
                return;
            }

            if ($configFieldValue) {
                $this->resetValidation('editSelectedFieldGetConfigValueFrom');
                $this->addError('editSelectedFieldGetConfigValueFrom', "Predefined field value already assigned in field: {$configFieldValue->name}");
                return;
            }
        }

        if ($this->editSelectedFieldIsHeaderField && !$this->editSelectedFieldAssignedColumnNumber) {
            $this->addError('editSelectedFieldAssignedColumnNumber', 'Column field is required');
            return;
        }

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
                'is_enabled' => $this->editSelectedFieldEnabled,
                'assigned_column' => $this->editSelectedFieldAssignedColumnNumber ?? null,
                'is_header_field' => $this->editSelectedFieldIsHeaderField,
                'config' => Field::setConfig($this->editSelectedFieldGetConfigValueFrom)
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
        $this->selectedFormFieldAsPredefinedField = false;
    }

    public function saveSelectedFormAddedFields()
    {
        if ($this->selectedFormFieldAsPredefinedField) {
            if (!$this->selectedFormFieldGetConfigValueFrom) {
                $this->addError('selectedFormFieldGetConfigValueFrom', 'This field is required');
                return;
            } else {
                $this->resetValidation('selectedFormFieldGetConfigValueFrom');
            }
        }

        if ($this->selectedFormFieldAsHeaderField) {
            if (!$this->selectedFormFieldColumnNumber) {
                $this->addError('selectedFormFieldColumnNumber', 'This field is required');
                return;
            } else {
                $this->resetValidation('selectedFormFieldColumnNumber');
            }
        }

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
                'config' => Field::setConfig($this->selectedFormFieldGetConfigValueFrom)
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
            'selectedFormFieldAsPredefinedField',
        ]);

        $this->resetValidation();
        $this->dispatchBrowserEvent('selected-form-clear-form-fields');
        $this->emitSelf('loadHelpTopics');
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
                    'config' => $field['config']
                ]);
            }

            $this->actionOnEditFormAddedField();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function actionOnEditFormAddedField()
    {
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
        $this->editSelectedFieldConfig = null;
    }

    public function updatedSelectedFormFieldName($value)
    {
        $this->selectedFormVariableName = $this->convertToVariable($value);
    }

    public function updatedSelectedFormFieldAsPredefinedField($value)
    {
        $takenConfigValues = Field::where('form_id', $this->selectedFormId)
            ->get()
            ->filter(function ($field) {
                $config = $field->config; // Directly access the ArrayObject
                return isset($config['get_value_from']['value']);
            })
            ->pluck('config')
            ->map(function ($config) {
                return $config['get_value_from']['value'];
            })
            ->values()
            ->toArray();

        if ($value) {
            $this->dispatchBrowserEvent('add-selected-form-show-select-get-config-value-from', [
                'takenPredefinedFieldValues' => $takenConfigValues,
                'selectedFormFieldPredefinedFieldValues' => PredefinedFieldValueEnum::getOptions(),
            ]);
        } else {
            $this->selectedFormFieldGetConfigValueFrom = null;
            $this->resetValidation('selectedFormFieldGetConfigValueFrom');
        }
    }

    public function updatedSelectedFormFieldAsHeaderField($value)
    {
        if ($value) {
            $this->dispatchBrowserEvent('add-selected-form-show-select-column-number');
        } else {
            $this->selectedFormFieldColumnNumber = null;
            $this->resetValidation('selectedFormFieldColumnNumber');
        }
    }

    public function convertToVariable($variable)
    {
        return preg_replace('/[^a-zA-Z0-9_.]+/', '_', strtolower(trim($variable)));
    }

    public function render()
    {
        return view('livewire.staff.help-topic.help-topic-list', [
            'helpTopics' => $this->queryHelpTopics(),
            'addFormFieldFieldTypes' => Options::forEnum(FieldTypesEnum::class)->toArray(),
        ]);
    }
}
