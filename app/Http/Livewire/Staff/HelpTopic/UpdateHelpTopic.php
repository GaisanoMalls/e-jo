<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\BasicModelQueries;
use App\Models\HelpTopic;
use Livewire\Component;

class UpdateHelpTopic extends Component
{
    use BasicModelQueries;

    public HelpTopic $helpTopic;

    // public $serviceLevelAgreements = [];
    public $name;

    public function mount(HelpTopic $helpTopic)
    {
        $this->name = $this->helpTopic->name;
        // $this->serviceLevelAgreements = $this->queryServiceLevelAgreements();
    }

    public function rules()
    {
        return [
            'name' => "required|unique:help_topics,name,{$this->helpTopic->id}"
        ];
    }

    public function updateHelpTopic()
    {
        $this->validate();
    }

    public function render()
    {
        return view('livewire.staff.help-topic.update-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'teams' => $this->queryTeams(),
        ]);
    }
}
