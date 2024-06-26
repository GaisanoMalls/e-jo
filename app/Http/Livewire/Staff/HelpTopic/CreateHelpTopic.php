<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\HelpTopic;
use App\Models\Role;
use App\Models\ServiceDepartmentChildren;
use App\Models\SpecialProject;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\Log;


class CreateHelpTopic extends Component
{
    use Utils, BasicModelQueries;


    public $approvalLevels = [1, 2, 3, 4, 5];
    public $levelApprovers = null;
    public $level1Approvers = [];
    public $level2Approvers = [];
    public $level3Approvers = [];
    public $level4Approvers = [];
    public $level5Approvers = [];
    public $approvalLevelSelected = false;

    public $buDepartment;

    public $buDepartments;

    public $configurations = [];

    public $selectedBuDepartment;
    public $selectedApproversCount = 0;

    public function mount()
    {
        $this->buDepartments = $this->queryBUDepartments();
    }

    public function saveConfiguration()
    {
        $approversCount = array_sum([
            count($this->level1Approvers),
            count($this->level2Approvers),
            count($this->level3Approvers),
            count($this->level4Approvers),
            count($this->level5Approvers)
        ]);

        // Get the selected BU Department name
        $buDepartmentName = collect($this->buDepartments)->firstWhere('id', $this->selectedBuDepartment)['name'];

        $this->configurations[] = [
            'bu_department_id' => $this->selectedBuDepartment,
            'bu_department_name' => $buDepartmentName,
            'approvers_count' => $approversCount,
        ];

        // Reset the selected fields
        $this->selectedBuDepartment = null;
        $this->approvalLevelSelected = false;
        $this->dispatchBrowserEvent('reset-select-fields');
    }


    public function removeConfiguration($index)
    {
        array_splice($this->configurations, $index, 1);
    }


    public function updatedApprovalLevelSelected()
    {
        $this->getFilteredApprovers(1);
    }

    public function updatedLevel1Approvers()
    {
        $this->getFilteredApprovers(2);
    }

    public function updatedLevel2Approvers()
    {
        $this->getFilteredApprovers(3);
    }

    public function updatedLevel3Approvers()
    {
        $this->getFilteredApprovers(4);
    }

    public function updatedLevel4Approvers()
    {
        $this->getFilteredApprovers(5);
    }
    public function updatedBuDepartment($value)
    {
        $this->getFilteredApprovers($value);
    }



    public function getFilteredApprovers($level)
    {
        $selectedApprovers = array_merge(
            (array)$this->level1Approvers,
            (array)$this->level2Approvers,
            (array)$this->level3Approvers,
            (array)$this->level4Approvers,
            (array)$this->level5Approvers
        );

        $filteredApprovers = User::with(['profile', 'roles'])
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->whereNotIn('id', $selectedApprovers)
            ->orderByDesc('created_at')
            ->get();

        $this->dispatchBrowserEvent('load-approvers', ['approvers' => $filteredApprovers, 'level' => $level]);
    }


    public function render()
    {
        return view('livewire.staff.help-topic.create-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'buDepartments' => $this->queryBUDepartments(),
            'configurations' => $this->configurations,
        ]);
    }
}
