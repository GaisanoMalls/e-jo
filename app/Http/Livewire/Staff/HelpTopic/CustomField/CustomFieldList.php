<?php

namespace App\Http\Livewire\Staff\HelpTopic\CustomField;

use App\Models\Field;
use Exception;
use Livewire\Component;

class CustomFieldList extends Component
{
    public $fieldName;

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

    public function render()
    {
        return view('livewire.staff.help-topic.custom-field.custom-field-list', [
            'fields' => Field::orderByDesc('created_at')->get(),
        ]);
    }
}
