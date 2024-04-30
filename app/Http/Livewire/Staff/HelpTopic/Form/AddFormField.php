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
    public $help_topic;
    public $form_name;
    public $name;
    public $type;
    public $variable_name;
    public $is_required;
    public $addedFields = [];

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

    public function updatedName($value)
    {
        $this->variable_name = preg_replace('/\s+/', '_', strtolower(trim($value)));
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
                'variable_name' => $this->variable_name,
                'is_required' => $this->is_required,
            )
        );

        $this->reset('name', 'type', 'variable_name', 'is_required');
        $this->resetValidation();
        $this->dispatchBrowserEvent('clear-form-fields');
    }

    public function removeField(int $field_key)
    {
        foreach (array_keys($this->addedFields) as $key) {
            if ($field_key === $key) {
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
                    'help_topic_id' => $this->help_topic,
                    'name' => $this->form_name
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
