<?php

namespace App\Http\Livewire\Staff\Tags;

use App\Models\Tag;
use Livewire\Component;

class TagList extends Component
{
    public $tags = [];
    public $name, $tagDeleteId, $tagUpdateId;

    protected $listeners = ["loadTags" => "fetchTags"];

    public function fetchTags()
    {
        $this->tags = Tag::orderBy('created_at', 'desc')->get();
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:tags,name,' . $this->tagUpdateId,
        ];
    }

    public function showEditTagModal(Tag $tag)
    {
        $this->tagUpdateId = $tag->id;
        $this->name = $tag->name;
        $this->dispatchBrowserEvent('show-edit-tag-modal');
    }

    public function updateTag()
    {
        $this->validate();

        try {
            Tag::where('id', $this->tagUpdateId)
                ->update([
                    'name' => $this->name,
                    'slug' => \Str::slug($this->name)
                ]);

            $this->fetchTags();
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Tag updated');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function showDeleteTagModal(Tag $tag)
    {
        $this->tagDeleteId = $tag->id;
        $this->name = $tag->name;
        $this->dispatchBrowserEvent('show-delete-tag-modal');
    }

    public function delete()
    {
        try {
            Tag::find($this->tagDeleteId)->delete();
            $this->tagDeleteId = '';
            $this->fetchTags();
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Tag deleted');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.tags.tag-list', [
            'tags' => $this->fetchTags(),
        ]);
    }
}