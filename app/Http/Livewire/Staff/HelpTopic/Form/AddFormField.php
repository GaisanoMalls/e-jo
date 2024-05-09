<?php

namespace App\Http\Livewire\Staff\HelpTopic\Form;

use App\Enums\FieldRequiredOptionEnum;
use App\Enums\FieldTypesEnum;
use App\Http\Requests\SysAdmin\Manage\HelpTopic\CustomField\CustomFieldRequest;
use App\Http\Traits\AppErrorLog;
use App\Models\Field;
use App\Models\Form;
use App\Models\HelpTopic;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;
use Spatie\LaravelOptions\Options;

class AddFormField extends Component
{
    public $helpTopic;
    public $formName;
    public $name;
    public $type;
    public $variableName;
    public $is_required;
    public $addedFields = [];
    public $editingFieldId;
    public $editingFieldName;
    public $editingFieldType;
    public $editingFieldIsRequired;
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
        $this->emit('loadCustomFieldList');
        $this->dispatchBrowserEvent('clear-form');
    }

    public function convertToVariable($value)
    {
        return preg_replace('/[^a-zA-Z0-9.]+/', '_', strtolower(trim($value)));
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
        if (is_null($this->name)) {
            $this->addError('name', 'Field name is required');
            return;
        }

        if (is_null($this->type)) {
            $this->addError('type', 'Field type is required');
            return;
        }

        array_push(
            $this->addedFields,
            array(
                'name' => $this->name,
                'label' => $this->name,
                'type' => $this->type,
                'variable_name' => $this->variableName,
                'is_required' => $this->is_required,
            )
        );

        $this->reset('name', 'type', 'variableName', 'is_required');
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
                    $this->editingFieldIsRequired = $field['is_required'];
                    $this->editingFieldVariableName = $field['variable_name'];

                    $this->dispatchBrowserEvent('edit-added-field-show-select-field', [
                        'isEditing' => true,
                        'currentFieldType' => $this->editingFieldType,
                        'currentFieldIsRequired' => $this->editingFieldIsRequired
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
                    $field['is_required'] = $this->editingFieldIsRequired;
                    $field['variable_name'] = $this->editingFieldVariableName;
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
        $this->editingFieldName = '';
        $this->editingFieldType = '';
        $this->editingFieldIsRequired = '';
        $this->editingFieldVariableName = '';
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
            } else {
                $form = Form::create([
                    'help_topic_id' => $this->helpTopic,
                    'name' => $this->formName
                ]);

                foreach ($this->addedFields as $field) {
                    $form->fields()->create([
                        'name' => $field['name'],
                        'label' => $field['name'],
                        'type' => $field['type'],
                        'variable_name' => $field['variable_name'],
                        'is_required' => $field['is_required'] == FieldRequiredOptionEnum::YES ? true : false
                    ]);
                }
                $this->actionOnSubmit();
            }

            // Field::create([
            //     'form_id' => $form->id,
            //     'name' => $this->name,
            //     'label' => $this->name,
            //     'type' => $this->type,
            //     'variable_name' => $this->variable_name,
            //     'is_required' => $this->is_required == FieldRequiredOptionEnum::YES ? true : false,
            // ]);


        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.help-topic.form.add-form-field', [
            'fieldRequiredOption' => Options::forEnum(FieldRequiredOptionEnum::class)->toArray(),
            'fieldTypes' => Options::forEnum(FieldTypesEnum::class)->toArray(),
            'helpTopics' => HelpTopic::all(['id', 'name'])
        ]);
    }
}
