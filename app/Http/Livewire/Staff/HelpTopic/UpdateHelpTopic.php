<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\BasicModelQueries;
use App\Models\HelpTopic;
use App\Models\LevelApprover;
use Livewire\Component;

class UpdateHelpTopic extends Component
{
    use BasicModelQueries;

    public HelpTopic $helpTopic;
    public $name;
    public $approvers = [];

    public function mount(HelpTopic $helpTopic)
    {
        $this->name = $this->helpTopic->name;
        $this->approvers = $this->queryApprovers();
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

    public function getCurrentApprovers()
    {
        $currentApprovers = [];
        $approvers = $this->queryApprovers();
        $levelApprovers = LevelApprover::where('help_topic_id', $this->helpTopic->id)->get();

        foreach ($this->helpTopic->levels as $level) {
            foreach ($levelApprovers as $levelApprover) {
                foreach ($approvers as $approver) {
                    if ($levelApprover->user_id == $approver->id && $levelApprover->level_id == $level->id) {
                        array_push($currentApprovers, [
                            'id' => $approver->id,
                            'name' => $approver->profile->first_name,
                            'level' => $level->value
                        ]);
                    }
                }
            }
        }

        return $currentApprovers;
    }

    public function render()
    {
        return view('livewire.staff.help-topic.update-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'levelOfApprovals' => $this->queryLevelOfApprovals(),
            'teams' => $this->queryTeams(),
            'currentApprovers' => $this->getCurrentApprovers(),
            'approvers' => $this->queryApprovers(),
            'levels' => $this->helpTopic->levels,
            'levelApprovers' => LevelApprover::where('help_topic_id', $this->helpTopic->id)->get(),
        ]);
    }
}
