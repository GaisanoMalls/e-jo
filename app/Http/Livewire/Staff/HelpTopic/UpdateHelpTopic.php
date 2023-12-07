<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\BasicModelQueries;
use App\Models\HelpTopic;
use App\Models\LevelApprover;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UpdateHelpTopic extends Component
{
    use BasicModelQueries;

    public HelpTopic $helpTopic;
    public $teams = [];
    public $level1Approvers = [];
    public $level2Approvers = [];
    public $level3Approvers = [];
    public $level4Approvers = [];
    public $level5Approvers = [];
    public $name;
    public $sla;
    public $service_department;
    public $team;
    public $level_of_approval;
    public $amount;
    public $max_amount = 50000;
    public $fpmCOOApprover;

    public function mount(HelpTopic $helpTopic)
    {
        $this->name = $helpTopic->name;
        $this->sla = $helpTopic->service_level_agreement_id;
        $this->service_department = $helpTopic->service_department_id;
        $this->team = $helpTopic->team_id;
        $this->amount = $helpTopic->specialProject ? $helpTopic->specialProject->amount : null;
        $this->level_of_approval = $helpTopic->levels->pluck('id')->last();
        $this->fpmCOOApprover = $this->helpTopic->specialProject->fmp_coo_approver['approver_id'] ?? null;
        $this->teams = Team::whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $helpTopic->service_department_id))->get();
        for ($count = 1; $count <= 5; $count++) {
            $this->{"level{$count}Approvers"} = $this->{"getLevel{$count}Approvers"}();
        }
    }

    public function rules()
    {
        $rules = [
            'name' => "required|unique:help_topics,name,{$this->helpTopic->id}",
            'sla' => 'required',
            'service_department' => 'required',
            'team' => 'nullable',
            'level_of_approval' => 'nullable',
            'amount' => 'nullable',
            'teams' => '',
        ];

        // if (!is_null($this->helpTopic->specialProject)) {
        //     $rules['amount'] = 'required';
        //     $rules['level_of_approval'] = 'required';

        //     for ($count = 1; $count <= 5; $count++) {
        //         if (empty($this->{"level{$count}Approvers"})) {
        //             $rules["level{$count}Approvers"] = 'required';
        //         }
        //     }
        // }

        return $rules;
    }

    public function updateHelpTopic()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->helpTopic->update([
                    'service_department_id' => $this->service_department,
                    'team_id' => $this->team,
                    'service_level_agreement_id' => $this->sla,
                    'name' => $this->name,
                    'slug' => \Str::slug($this->name),
                ]);

                if (!is_null($this->helpTopic->specialProject)) {
                    $this->helpTopic->specialProject->update(['amount' => $this->amount]);
                    // Sync all levels first
                    $this->helpTopic->levels()->sync(range(1, $this->level_of_approval));
                    // Delete existing level approvers for the current topic
                    LevelApprover::where(['help_topic_id' => $this->helpTopic->id])->delete();
                    // Iterate through selected level approvers
                    for ($level = 1; $level <= $this->level_of_approval; $level++) {
                        foreach ($this->{"level{$level}Approvers"} as $approver) {
                            LevelApprover::create([
                                'help_topic_id' => $this->helpTopic->id,
                                'level_id' => $level,
                                'user_id' => $approver,
                            ]);
                        }
                    }
                }
            });

            flash()->addSuccess('Help topic successfully updated.');

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong.');
        }
    }

    public function updatedServiceDepartment()
    {
        $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $this->service_department))->get();
        $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
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
                            'level' => (int) $level->value,
                        ]);
                    }
                }
            }
        }

        return $currentApprovers;
    }

    public function getLevel1Approvers()
    {
        $level1Approvers = [];
        $levelApprovers = LevelApprover::where('help_topic_id', $this->helpTopic->id)->get();

        foreach ($levelApprovers as $levelApprover) {
            if ($levelApprover->level_id === 1) {
                array_push($level1Approvers, [
                    'user_id' => $levelApprover->user_id,
                    'level_id' => $levelApprover->level_id,
                ]);
            }
        }

        return $level1Approvers;
    }

    public function getLevel2Approvers()
    {
        $level2Approvers = [];
        $levelApprovers = LevelApprover::where('help_topic_id', $this->helpTopic->id)->get();

        foreach ($levelApprovers as $levelApprover) {
            if ($levelApprover->level_id === 2) {
                array_push($level2Approvers, [
                    'user_id' => $levelApprover->user_id,
                    'level_id' => $levelApprover->level_id,
                ]);
            }
        }

        return $level2Approvers;
    }

    public function getLevel3Approvers()
    {
        $level3Approvers = [];
        $levelApprovers = LevelApprover::where('help_topic_id', $this->helpTopic->id)->get();

        foreach ($levelApprovers as $levelApprover) {
            if ($levelApprover->level_id === 3) {
                array_push($level3Approvers, [
                    'user_id' => $levelApprover->user_id,
                    'level_id' => $levelApprover->level_id,
                ]);
            }
        }

        return $level3Approvers;
    }

    public function getLevel4Approvers()
    {
        $level4Approvers = [];
        $levelApprovers = LevelApprover::where('help_topic_id', $this->helpTopic->id)->get();

        foreach ($levelApprovers as $levelApprover) {
            if ($levelApprover->level_id === 4) {
                array_push($level4Approvers, [
                    'user_id' => $levelApprover->user_id,
                    'level_id' => $levelApprover->level_id,
                ]);
            }
        }

        return $level4Approvers;
    }

    public function getLevel5Approvers()
    {
        $level5Approvers = [];
        $levelApprovers = LevelApprover::where('help_topic_id', $this->helpTopic->id)->get();

        foreach ($levelApprovers as $levelApprover) {
            if ($levelApprover->level_id === 5) {
                array_push($level5Approvers, [
                    'user_id' => $levelApprover->user_id,
                    'level_id' => $levelApprover->level_id,
                ]);
            }
        }

        return $level5Approvers;
    }

    public function render()
    {
        return view('livewire.staff.help-topic.update-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'levelOfApprovals' => $this->queryLevelOfApprovals(),
            'currentApprovers' => $this->getCurrentApprovers(),
            'approvers' => $this->queryApprovers(),
        ]);
    }
}
