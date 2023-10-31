<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\BasicModelQueries;
use Livewire\Component;

class HelpTopicList extends Component
{
    use BasicModelQueries;

    protected $listeners = ['loadHelpTopics' => '$refresh'];

    public function render()
    {
        return view('livewire.staff.help-topic.help-topic-list', [
            'helpTopics' => $this->queryHelpTopics(),
        ]);
    }
}
