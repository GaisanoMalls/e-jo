<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\Form;
use App\Models\HelpTopic;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;

class HelpTopicList extends Component
{
    use BasicModelQueries;

    public Collection $formFields;
    public Collection $helpTopicForms;
    public $helpTopicId;
    public $helpTopicName;
    public $formName;
    public $formFieldName;
    public $fieldVarNames;

    public function mount()
    {
        $this->formFields = collect([]);
    }

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
        $this->helpTopicForms = Form::where('help_topic_id', $helpTopic->id)->get();
    }

    public function deleteHelpTopicForm(Form $form)
    {
        try {
            $form->delete();
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.help-topic.help-topic-list', [
            'helpTopics' => $this->queryHelpTopics(),
        ]);
    }
}
