<?php

namespace App\Http\Livewire\Staff\Tags;

use App\Http\Requests\SysAdmin\Manage\Tag\StoreTagRequest;
use App\Http\Traits\Utils;
use App\Models\Tag;
use Livewire\Component;

class CreateTag extends Component
{
    use Utils;

    public $name;

    public function rules()
    {
        return (new StoreTagRequest())->rules();
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
    }

    private function actionOnSubmit()
    {
        sleep(1);
        $this->emit('loadTags');
        $this->clearFormField();
    }

    public function saveTag()
    {
        $this->validate();

        try {
            Tag::create([
                'name' => $this->name,
                'slug' => \Str::slug($this->name)
            ]);

            $this->actionOnSubmit();
            flash()->addSuccess('Tag created');

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.tags.create-tag');
    }
}