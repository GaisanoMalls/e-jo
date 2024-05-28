<?php

namespace App\Http\Livewire\Staff\HelpTopic;

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

class HelpTopicList extends Component
{
    use BasicModelQueries;

    public ?Collection $helpTopicForms = null;
    public $helpTopicId;
    public $helpTopicName;
    public $selectedFormId;
    public $selectedFormName;
    public $selectedFormFieldName;
    public $selectedFormVariableName;
    public $selectedFormFieldType;
    public $selectedFormFieldIsRequired;
    public $selectedFormAddedFields = [];

    protected $listeners = ['loadHelpTopics' => '$refresh'];

    public function deleteHelpTopic(HelpTopic $helpTopic)
    {
        $this->helpTopicId = $helpTopic->id;
        $this->helpTopicName = $helpTopic->name;
        $this->dispatchBrowserEvent('show-delete-help-topic-modal');
    }

    public function delete()
    {
        try {
            $helpTopic = HelpTopic::find($this->helpTopicId);
            if ($helpTopic) {
                $helpTopic->delete();
                $this->helpTopicId = null;
                $this->dispatchBrowserEvent('close-modal');
                noty()->addSuccess('Help topic successfully deleted');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function viewHelpTopicForm(HelpTopic $helpTopic)
    {
        $this->helpTopicForms = $helpTopic->forms()->get(['id', 'name', 'visible_to']);
    }

    public function deleteHelpTopicForm(Form $form)
    {
        try {
            $form->delete();
            $this->emit('loadHelpTopics');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function deleteFormField(Field $field)
    {
        try {
            $field->delete();
            $this->emit('loadHelpTopics');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function addFieldToSelectedForm(Form $form)
    {
        $this->selectedFormId = $form->id;
        $this->selectedFormName = $form->name;
    }

    public function convertToVariable($value)
    {
        return preg_replace('/[^a-zA-Z0-9_.]+/', '_', strtolower(trim($value)));
    }

    public function updatedSelectedFormFieldName($value)
    {
        $this->selectedFormVariableName = $this->convertToVariable($value);
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('loadHelpTopics');
        $this->dispatchBrowserEvent('selected-form-clear-form-fields');
    }

    public function saveAddedFields()
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
            $this->addError('selectedFormFieldIsRequired', 'Please choose if this field is required or not');
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
            ]
        );

        $this->reset('selectedFormFieldName', 'selectedFormFieldType', 'selectedFormVariableName', 'selectedFormFieldIsRequired');
        $this->resetValidation();
        $this->dispatchBrowserEvent('selected-form-clear-form-fields');
    }

    public function selectedFormRemoveField(int $fieldKey)
    {
        foreach (array_keys($this->selectedFormAddedFields) as $key) {
            if ($fieldKey === $key) {
                unset($this->selectedFormAddedFields[$key]);
            }
        }
    }

    public function selectedFormSaveField()
    {
        try {
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
                    'is_required' => $field['is_required'] == FieldRequiredOptionEnum::YES ? true : false
                ]);
            }
            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.help-topic.help-topic-list', [
            'helpTopics' => $this->queryHelpTopics(),
            'addFormFieldFieldTypes' => Options::forEnum(FieldTypesEnum::class)->toArray(),
            'addFormFieldUserRoles' => Options::forModels(Role::class)->toArray(),
            'addFormFieldRequiredOption' => Options::forEnum(FieldRequiredOptionEnum::class)->toArray(),
        ]);
    }
}
