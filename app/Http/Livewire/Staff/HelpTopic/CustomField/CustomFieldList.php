<?php

namespace App\Http\Livewire\Staff\HelpTopic\CustomField;

use App\Enums\FieldRequiredOptionEnum;
use App\Enums\FieldTypesEnum;
use App\Models\Field;
use Exception;
use Livewire\Component;
use Spatie\LaravelOptions\Options;

class CustomFieldList extends Component
{
    public $name;
    public $type;
    public $is_required;
    public $variable_name;
    public $editingFieldId;

    protected $listeners = ['loadCustomFieldList' => '$refresh'];

    public function deleteField(Field $field)
    {
        try {
            $field->delete();
        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function toggleEdit(Field $field)
    {
        $this->name = $field->name;
        $this->variable_name = $field->variable_name;
        $this->editingFieldId = $field->id == $this->editingFieldId ? null : $field->id;
        $this->dispatchBrowserEvent('show-dropdown-fields', [
            'currentFieldType' => $field->type,
            'currentRequiredField' => $field->is_required,
        ]);
    }

    public function toggleField(Field $field)
    {
        $field->update(['is_enabled' => !$field->is_enabled]);
    }

    public function updatedName($value)
    {
        $this->variable_name = preg_replace('/\s+/', '_', strtolower(trim($value)));
    }

    public function updateCustomeField()
    {
        Field::where('id', $this->editingFieldId)->update([
            'name' => $this->name,
            'label' => $this->name,
            'type' => $this->type,
            'variable_name' => $this->variable_name,
            'is_required' => $this->is_required,
        ]);

        $this->editingFieldId = null;
    }

    public function render()
    {
        return view('livewire.staff.help-topic.custom-field.custom-field-list', [
            'fields' => Field::orderByDesc('created_at')->get(),
            'editFieldTypes' => Options::forEnum(FieldTypesEnum::class)->toArray(),
            'editFieldRequiredOption' => Options::forEnum(FieldRequiredOptionEnum::class)->toArray(),
        ]);
    }
}
