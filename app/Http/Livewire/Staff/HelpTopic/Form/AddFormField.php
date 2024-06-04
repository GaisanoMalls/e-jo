<?php

namespace App\Http\Livewire\Staff\HelpTopic\Form;

use App\Enums\FieldEnableOptionEnum;
use App\Enums\FieldRequiredOptionEnum;
use App\Enums\FieldTypesEnum;
use App\Http\Requests\SysAdmin\Manage\HelpTopic\CustomField\CustomFieldRequest;
use App\Http\Traits\AppErrorLog;
use App\Models\Form;
use App\Models\HelpTopic;
use App\Models\Role;
use Exception;
use Livewire\Component;
use Spatie\LaravelOptions\Options;

class AddFormField extends Component
{
    public $formName;
    public $helpTopic;
    public $visibleTo = [];
    public $name;
    public $type;
    public $variableName;
    public $addedFields = [];
    public $is_required = false;
    public $is_enabled = false;
    public $editingFieldId;
    public $editingFieldName;
    public $editingFieldType;
    public $editingFieldRequired;
    public $editingFieldEnable;
    public $editingFieldVariableName;

    public function rules()
    {
        return (new CustomFieldRequest())->rules();
    }

    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('clear-form');
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('loadHelpTopics');
        $this->dispatchBrowserEvent('clear-form');
    }

    public function convertToVariable($value)
    {
        return preg_replace('/[^a-zA-Z0-9_.]+/', '_', strtolower(trim($value)));
    }

    public function updatedName($value)
    {
        $this->variableName = $this->convertToVariable($value);
    }

    public function updatedEditingFieldName($value)
    {
        $this->editingFieldVariableName = $this->convertToVariable($value);
    }

    public function addField()
    {
        if (!$this->name) {
            $this->addError('name', 'Field name is required');
            return;
        }

        if (!$this->type) {
            $this->addError('type', 'Field type is required');
            return;
        }

        if (!$this->is_required) {
            $this->addError('is_required', 'Please choose if this field is required or not');
            return;
        }

        array_push(
            $this->addedFields,
            [
                'name' => $this->name,
                'label' => $this->name,
                'type' => $this->type,
                'variable_name' => $this->variableName,
                'is_required' => $this->is_required == FieldRequiredOptionEnum::YES->value,
                'is_enabled' => $this->is_enabled == FieldEnableOptionEnum::YES->value
            ]
        );

        $this->reset('name', 'type', 'variableName', 'is_required', 'is_enabled');
        $this->resetValidation();
        $this->dispatchBrowserEvent('clear-form-fields');
    }

    public function toggleEditAddedField(int $fieldKey)
    {
        try {
            $this->editingFieldId = $fieldKey;

            foreach ($this->addedFields as $key => $field) {
                if ($this->editingFieldId === $key) {
                    $this->editingFieldName = $field['name'];
                    $this->editingFieldType = $field['type'];
                    $this->editingFieldRequired = $field['is_required'] == FieldRequiredOptionEnum::YES->value;
                    $this->editingFieldEnable = $field['is_enabled'] == FieldEnableOptionEnum::YES->value;
                    $this->editingFieldVariableName = $field['variable_name'];

                    $this->dispatchBrowserEvent('edit-added-field-show-select-field', [
                        'currentFieldType' => $this->editingFieldType,
                        'currentFieldRequired' => $this->editingFieldRequired == FieldRequiredOptionEnum::YES->value,
                        'currentFieldEnable' => $this->editingFieldEnable == FieldEnableOptionEnum::YES->value
                    ]);
                }
            }

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updateAddedField(int $fieldKey)
    {
        try {
            foreach ($this->addedFields as $key => &$field) {
                if ($this->editingFieldId === $fieldKey && $key === $fieldKey) {
                    $field['name'] = $this->editingFieldName;
                    $field['type'] = $this->editingFieldType;
                    $field['variable_name'] = $this->editingFieldVariableName;
                    $field['is_required'] = $this->editingFieldRequired == FieldRequiredOptionEnum::YES->value;
                    $field['is_enabled'] = $this->editingFieldEnable == FieldEnableOptionEnum::YES->value;
                }
            }
            $this->editFieldAction();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function cancelEditAddedField(int $fieldKey)
    {
        if ($this->editingFieldId === $fieldKey) {
            $this->editFieldAction();
        }
    }

    public function editFieldAction()
    {
        $this->editingFieldId = null;
        $this->editingFieldName = null;
        $this->editingFieldType = null;
        $this->editingFieldRequired = false;
        $this->editingFieldEnable = false;
        $this->editingFieldVariableName = null;

    }

    public function removeField(int $fieldKey)
    {
        foreach (array_keys($this->addedFields) as $key) {
            if ($fieldKey === $key) {
                unset($this->addedFields[$key]);
            }
        }
    }

    public function saveForm()
    {
        $this->validate();

        try {
            if (empty($this->addedFields)) {
                session()->flash('required_form_fields_error', 'Form fields are required');
                return;
            }

            $form = Form::create([
                'help_topic_id' => $this->helpTopic,
                'visible_to' => $this->visibleTo,
                'name' => $this->formName
            ]);

            foreach ($this->addedFields as $field) {
                $form->fields()->create([
                    'name' => $field['name'],
                    'label' => $field['name'],
                    'type' => $field['type'],
                    'variable_name' => $field['variable_name'],
                    'is_required' => $field['is_required'],
                    'is_enabled' => $field['is_enabled'],
                ]);
            }
            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.help-topic.form.add-form-field', [
            'fieldRequiredOption' => Options::forEnum(FieldRequiredOptionEnum::class)->toArray(),
            'fieldEnableOption' => Options::forEnum(FieldEnableOptionEnum::class)->toArray(),
            'fieldTypes' => Options::forEnum(FieldTypesEnum::class)->toArray(),
            'userRoles' => Options::forModels(Role::class)->toArray(),
            'helpTopics' => HelpTopic::all(['id', 'name'])
        ]);
    }
}
