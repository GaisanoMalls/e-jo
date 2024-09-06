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
    public bool $isForTicketNumber = false;
    public bool $isRequired = false;
    public bool $isEnabled = false;
    public ?int $editingFieldId = null;
    public ?int $editingHeaderFieldId = null;
    public ?string $editingFieldName = null;
    public ?string $editingFieldType = null;
    public ?string $editingFieldVariableName = null;
    public ?string $editingAssignedColumn = null; // 1, 2, 'None'
    public bool $editingFieldRequired = false;
    public bool $editingFieldEnable = false;
    public bool $editingAsHeaderField = false;
    public bool $editingIsForTicketNumber = false;

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
        if ($value) {
            $this->dispatchBrowserEvent('show-select-column-number');
            $this->hasAssociatedTicketField();
        } else {
            $this->assignedColumn = null;
        }
    }

    public function updatedName($value)
    {
        $this->variableName = $this->convertToVariable($value);
    }

    public function updatedEditingFieldName($value)
    {
        $this->editingFieldVariableName = $this->convertToVariable($value);
    }

    public function updatEdeditingAsHeaderField($value)
    {
        if (!$value) {
            $this->editingIsForTicketNumber = false;
            $this->dispatchBrowserEvent('disable-assigned-column-field');
        } else {
            $this->dispatchBrowserEvent('enable-assigned-column-field');
        }
    }

    public function hasAssociatedTicketField()
    {
        return !empty(array_filter($this->addedFields, function ($field) {
            return $field['isForTicketNumber'];
        }));
    }

    public function addField()
    {
        if ($this->asHeaderField) {
            if (!$this->assignedColumn) {
                $this->addError('assignedColumn', 'This field is required');
                return;
            } else {
                $this->resetValidation('assignedColumn');
            }
        }

        if ($this->hasAssociatedTicketField()) {
            session()->flash('has_associated_ticket_field', 'Oops! There is already a field associated with the ticket number.');
        }

        if (!$this->name) {
            $this->addError('name', 'Field name is required');
            return;
        } else {
            $this->resetValidation('name');
        }

        if (!$this->type) {
            $this->addError('type', 'Field type is required');
            return;
        } else {
            $this->resetValidation('type');
        }

        if (!$this->isRequired) {
            $this->addError('isRequired', 'This field is required');
            return;
        } else {
            $this->resetValidation('isRequired');
        }

        if (!$this->isEnabled) {
            $this->addError('isEnabled', 'This field is required');
            return;
        } else {
            $this->resetValidation('isEnabled');
        }

        array_push($this->addedFields, [
            'name' => $this->name,
            'label' => $this->name,
            'type' => $this->type,
            'variable_name' => $this->variableName,
            'isRequired' => $this->isRequired,
            'isEnabled' => $this->isEnabled,
            'asHeaderField' => $this->asHeaderField ? 'Yes' : 'No',
            'assignedColumn' => $this->asHeaderField ? $this->assignedColumn : null,
            'isForTicketNumber' => $this->isForTicketNumber
        ]);

        $this->reset(
            'name',
            'type',
            'variableName',
            'isRequired',
            'isEnabled',
            'assignedColumn',
            'asHeaderField',
            'isForTicketNumber',
            'isForTicketNumber'
        );
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
                    $this->editingFieldRequired = $field['isRequired'];
                    $this->editingFieldEnable = $field['isEnabled'] == FieldEnableOptionEnum::YES->value;
                    $this->editingFieldVariableName = $field['variable_name'];
                    $this->editingAsHeaderField = $field['asHeaderField'];
                    $this->editingAssignedColumn = $field['assignedColumn'];
                    $this->editingIsForTicketNumber = $field['isForTicketNumber'];

                    $this->dispatchBrowserEvent('edit-added-field-show-select-field', [
                        'currentFieldType' => $this->editingFieldType,
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
            if (!$this->editingAssignedColumn) {
                $this->addError('editingAssignedColumn', 'This field is required');
                return;
            } else {
                $this->resetValidation('editingAssignedColumn');
            }

            if (!$this->editingFieldName) {
                $this->addError('editingFieldName', 'This field name is required');
                return;
            } else {
                $this->resetValidation('editingFieldName');
            }

            if (!$this->editingFieldType) {
                $this->addError('editingFieldType', 'This field type is required');
                return;
            } else {
                $this->resetValidation('editingFieldType');
            }

            foreach ($this->addedFields as $key => &$field) {
                if ($this->editingFieldId === $fieldKey && $key === $fieldKey) {
                    $field['name'] = $this->editingFieldName;
                    $field['type'] = $this->editingFieldType;
                    $field['variable_name'] = $this->editingFieldVariableName;
                    $field['isRequired'] = $this->editingFieldRequired;
                    $field['isEnabled'] = $this->editingFieldEnable;
                    $field['asHeaderField'] = $this->editingAsHeaderField;
                    $field['assignedColumn'] = $this->editingAssignedColumn;
                    $field['isForTicketNumber'] = $this->editingIsForTicketNumber;
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
        $this->editingAssignedColumn = null;
        $this->editingFieldVariableName = null;
        $this->editingFieldRequired = false;
        $this->editingFieldEnable = false;
        $this->editingAsHeaderField = false;
        $this->editingIsForTicketNumber = false;
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
            if (empty($this->addedFields) && $this->name && $this->type && $this->isRequired && $this->isEnabled) {
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
                    'is_required' => $field['isRequired'],
                    'is_enabled' => $field['isEnabled'],
                    'assigned_column' => $field['assignedColumn'],
                    'is_header_field' => $field['asHeaderField'] == 'Yes' ? true : false,
                    'is_for_ticket_number' => $field['isForTicketNumber']
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
