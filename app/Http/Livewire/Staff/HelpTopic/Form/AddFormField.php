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
    public ?string $formName = null;
    public ?int $helpTopic = null;
    public array $visibleTo = [];
    public array $editableTo = [];
    public ?string $name = null;
    public ?string $type = null;
    public ?string $variableName = null;
    public array $addedFields = [];
    public array $addedHeaderFields = [];
    public array $fieldColumnNumber = [1, 2];
    public ?int $assignedColumn = null;
    public ?string $asHeaderField = null;
    public bool $is_required = false;
    public bool $is_enabled = false;
    public ?int $editingFieldId = null;
    public ?int $editingHeaderFieldId = null;
    public ?string $editingFieldName = null;
    public ?string $editingFieldType = null;
    public ?string $editingFieldRequired = null;
    public ?string $editingFieldEnable = null;
    public ?string $editingFieldVariableName = null;
    public ?string $editingAsHeaderField = null; // 'Yes' or 'No'
    public ?string $editingAssignedColumn = null; // 1, 2, 'None'

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

    public function updatedAsHeaderField($value)
    {
        $value
            ? $this->dispatchBrowserEvent('show-select-column-number')
            : $this->assignedColumn = null;
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
            $this->addError('is_required', 'This field is required');
            return;
        }

        if (!$this->is_enabled) {
            $this->addError('is_enabled', 'This field is required');
            return;
        }

        array_push($this->addedFields, [
            'name' => $this->name,
            'label' => $this->name,
            'type' => $this->type,
            'variable_name' => $this->variableName,
            'is_required' => $this->is_required == FieldRequiredOptionEnum::YES->value,
            'is_enabled' => $this->is_enabled == FieldEnableOptionEnum::YES->value,
            'as_header_field' => $this->asHeaderField ? 'Yes' : 'No',
            'assigned_column' => $this->asHeaderField ? $this->assignedColumn : null,
        ]);

        $this->reset('name', 'type', 'variableName', 'is_required', 'is_enabled', 'assignedColumn', 'asHeaderField');
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
                    $this->editingAsHeaderField = $field['as_header_field'];
                    $this->editingAssignedColumn = $field['assigned_column'];

                    $this->dispatchBrowserEvent('edit-added-field-show-select-field', [
                        'currentFieldType' => $this->editingFieldType,
                        'currentFieldRequired' => $this->editingFieldRequired == FieldRequiredOptionEnum::YES->value,
                        'currentFieldEnable' => $this->editingFieldEnable == FieldEnableOptionEnum::YES->value,
                        'currentAsHeaderField' => $this->editingAsHeaderField,
                        'currentAssignedCoumn' => $this->editingAssignedColumn,
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
            if (!$this->editingFieldName) {
                $this->addError('editingFieldName', 'This field name is required');
                return;
            }

            if (!$this->editingFieldType) {
                $this->addError('editingFieldType', 'This field type is required');
                return;
            }

            if (!$this->editingFieldRequired) {
                $this->addError('editingFieldRequired', 'This field is required');
                return;
            }

            if (!$this->editingFieldEnable) {
                $this->addError('editingFieldEnable', 'This field is required');
                return;
            }

            if (!$this->editingAsHeaderField) {
                $this->addError('editingAsHeaderField', 'This field is required');
                return;
            }

            if (!$this->editingAssignedColumn) {
                $this->addError('editingAssignedColumn', 'This field is required');
                return;
            }

            foreach ($this->addedFields as $key => &$field) {
                if ($this->editingFieldId === $fieldKey && $key === $fieldKey) {
                    $field['name'] = $this->editingFieldName;
                    $field['type'] = $this->editingFieldType;
                    $field['variable_name'] = $this->editingFieldVariableName;
                    $field['is_required'] = $this->editingFieldRequired == FieldRequiredOptionEnum::YES->value;
                    $field['is_enabled'] = $this->editingFieldEnable == FieldEnableOptionEnum::YES->value;
                    $field['as_header_field'] = $this->editingAsHeaderField;
                    $field['assigned_column'] = $this->editingAssignedColumn;
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
        $this->editingAsHeaderField = null;
        $this->editingAssignedColumn = null;
        $this->resetValidation();
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
            if (empty($this->addedFields) && $this->name && $this->type && $this->is_required && $this->is_enabled) {
                session()->flash('required_form_fields_error', 'Please add the fields first');
                return;
            }

            if (empty($this->addedFields)) {
                session()->flash('required_form_fields_error', 'Form fields are required');
                return;
            }

            $form = Form::create([
                'help_topic_id' => $this->helpTopic,
                'visible_to' => $this->visibleTo,
                'editable_to' => $this->editableTo,
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
                    'assigned_column' => $field['assigned_column'],
                    'is_header_field' => $field['as_header_field'] == 'Yes' ? true : false
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
