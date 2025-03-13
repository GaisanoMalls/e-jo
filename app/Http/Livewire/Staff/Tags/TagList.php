<?php

namespace App\Http\Livewire\Staff\Tags;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Str;
use Livewire\Component;

class TagList extends Component
{
    use BasicModelQueries;

    public $tags = [];
    public $name;
    public $tagDeleteId;
    public $tagUpdateId;

    protected $listeners = ["loadTags" => "fetchTags"];

    protected function rules()
    {
        return [
            'name' => "required|unique:tags,name,{$this->tagUpdateId}",
        ];
    }

    public function fetchTags()
    {
        $this->tags = $this->queryTags();
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function editTag(Tag $tag)
    {
        $this->tagUpdateId = $tag->id;
        $this->name = $tag->name;
        $this->resetValidation();
        $this->dispatchBrowserEvent('show-edit-tag-modal');
    }

    public function updateTag()
    {
        $this->validate();

        try {
            Tag::find($this->tagUpdateId)
                ->update([
                    'name' => $this->name,
                    'slug' => Str::slug($this->name),
                ]);

            $this->fetchTags();
            $this->clearFormField();
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('Tag updated successfully.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function deleteTag(Tag $tag)
    {
        $this->tagDeleteId = $tag->id;
        $this->name = $tag->name;
        $this->dispatchBrowserEvent('show-delete-tag-modal');
    }

    public function delete()
    {
        try {
            Tag::find($this->tagDeleteId)->delete();
            $this->tagDeleteId = null;
            $this->fetchTags();
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('Tag deleted successfully.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.tags.tag-list', [
            'tags' => $this->fetchTags(),
        ]);
    }
}