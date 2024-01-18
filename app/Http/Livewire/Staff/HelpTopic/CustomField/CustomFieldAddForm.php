<?php

namespace App\Http\Livewire\Staff\HelpTopic\CustomField;

use App\Enums\FieldRequiredOptionEnum;
use App\Enums\FieldTypesEnum;
use App\Http\Requests\SysAdmin\Manage\HelpTopic\CustomField\CustomFieldRequest;
use App\Http\Traits\Utils;
use App\Models\Field;
use Exception;
use Livewire\Component;
use Spatie\LaravelOptions\Options;

class CustomFieldAddForm extends Component
{
    public $name;
    public $type;
    public $variable_name;
    public $is_required;

    public function rules()
    {
        return (new CustomFieldRequest())->rules();
    }

    public function messages()
    {
        return (new CustomFieldRequest())->messages();
    }

    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('clear-form');
    }

    public function actionOnSubmit()
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

    public function saveCustomField()
    {
        $this->validate();

        try {
            Field::create([
                'name' => $this->name,
                'label' => $this->name,
                'type' => $this->type,
                'variable_name' => $this->variable_name,
                'is_required' => $this->is_required,
            ]);

            $this->actionOnSubmit();

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            ;
            noty()->addError('Oops, something went wrong.');
        }
    }

    public function render()
    {
        return view('livewire.staff.help-topic.custom-field.custom-field-add-form', [
            'fieldRequiredOption' => Options::forEnum(FieldRequiredOptionEnum::class)->toArray(),
            'fieldTypes' => Options::forEnum(FieldTypesEnum::class)->toArray(),
        ]);
    }
}
