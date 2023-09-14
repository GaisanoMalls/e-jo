<?php

namespace App\Http\Livewire\Tags;

use App\Models\Tag;
use Livewire\Component;

class ShowTags extends Component
{
    public $tags, $name, $tagDeleteId, $tagUpdateId;
    public Tag $tag;

    protected $listeners = ["loadTags" => "render"];

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
        Tag::where('id', $this->tagUpdateId)
            ->update(array_merge($this->validate(), [
                'slug' => \Str::slug($this->name)
            ]));

        $this->fetchTags();
        $this->dispatchBrowserEvent('close-modal');
        flash()->addSuccess('Tag updated');
    }

    public function showDeleteTagModal(Tag $tag)
    {
        $this->tagDeleteId = $tag->id;
        $this->name = $tag->name;
        $this->dispatchBrowserEvent('show-delete-tag-modal');
    }

    public function delete()
    {
        Tag::find($this->tagDeleteId)->delete();
        $this->fetchTags();
        $this->dispatchBrowserEvent('close-modal');
        $this->tagDeleteId = '';
        flash()->addSuccess('Tag deleted');
    }

    public function render()
    {
        return view('livewire.tags.show-tags', [
            'tags' => $this->fetchTags(),
        ]);
    }
}