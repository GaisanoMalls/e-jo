<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\BasicModelQueries;
use App\Models\HelpTopic;
use Exception;
use Livewire\Component;

class HelpTopicList extends Component
{
    use BasicModelQueries;

    public $helpTopicDeleteId;
    public $helpTopicName;

    protected $listeners = ['loadHelpTopics' => '$refresh'];

    public function deleteHelpTopic(HelpTopic $helpTopic)
    {
        $this->helpTopicDeleteId = $helpTopic->id;
        $this->helpTopicName = $helpTopic->name;
        $this->dispatchBrowserEvent('show-delete-help-topic-modal');
    }

    public function delete()
    {
        try {
            $helpTopic = HelpTopic::find($this->helpTopicDeleteId);
            if ($helpTopic) {
                $helpTopic->delete();
                sleep(1);
                $this->helpTopicDeleteId = null;
                $this->dispatchBrowserEvent('close-modal');
                flash()->addSuccess('Help topic successfully deleted');
            }
        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.help-topic.help-topic-list', [
            'helpTopics' => $this->queryHelpTopics(),
        ]);
    }
}
