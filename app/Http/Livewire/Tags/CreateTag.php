<?php

namespace App\Http\Livewire\Tags;

use App\Http\Requests\SysAdmin\Manage\Tag\StoreTagRequest;
use App\Models\Tag;
use Livewire\Component;

class CreateTag extends Component
{
    public ?string $name = null;

    public function rules()
    {
        return (new StoreTagRequest())->rules();
    }

    public function messages()
    {
        return (new StoreTagRequest())->messages();
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function saveTag()
    {
        $this->validate();
        try {
            Tag::create([
                'name' => $this->name,
                'slug' => $this->name
            ]);

            $this->emit('loadTags');
            $this->clearFormField();
            flash()->addSuccess('Tag created');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.tags.create-tag');
    }
}