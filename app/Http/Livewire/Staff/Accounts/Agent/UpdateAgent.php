<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UpdateAgent extends Component
{
    use BasicModelQueries, Utils;

    public User $agent;
    public $BUDepartments = [], $teams = [], $currentTeams = [], $selectedTeams = [];
    public $first_name, $middle_name, $last_name, $suffix, $email, $branch, $bu_department, $service_department;

    public function mount(User $agent)
    {
        $this->agent = $agent;
        $this->first_name = $agent->profile->first_name;
        $this->middle_name = $agent->profile->middle_name;
        $this->last_name = $agent->profile->last_name;
        $this->suffix = $agent->profile->suffix;
        $this->email = $agent->email;
        $this->branch = $agent->branch_id;
        $this->bu_department = $agent->department_id;
        $this->service_department = $agent->service_department_id;
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->teams = Team::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->currentTeams = $agent->teams->pluck('id')->toArray();
    }

    public function rules()
    {
        return [
            'branch' => 'required',
            'bu_department' => 'required',
            'selectedTeams' => 'required',
            'service_department' => 'required',
            'first_name' => 'required|min:2|max:100',
            'middle_name' => 'nullable|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'suffix' => 'nullable|min:1|max:4',
            'email' => "required|max:80|unique:users,email,{$this->agent->id}"
        ];
    }

    public function messages()
    {
        return [
            'selectedTeams.required' => 'Teams field is required.'
        ];
    }

    public function updatedBranch()
    {
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->teams = Team::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->dispatchBrowserEvent('get-branch-bu-departments-and-teams', [
            'BUDepartments' => $this->BUDepartments,
            'teams' => $this->teams
        ]);
    }

    public function updateAgentAccount()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->agent->update([
                    'branch_id' => $this->branch,
                    'department_id' => $this->bu_department,
                    'service_department_id' => $this->service_department,
                    'email' => $this->email
                ]);

                $this->agent->profile()->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'slug' => $this->slugify(implode(" ", [
                        $this->first_name,
                        $this->middle_name,
                        $this->last_name,
                        $this->suffix
                    ]))
                ]);

                $this->agent->teams()->sync($this->selectedTeams);
            });

            flash()->addSuccess("You have successfully updated the account for {$this->agent->profile->getFullName()}.");

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addError('Failed to update the agent.');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.agent.update-agent', [
            'agentSuffixes' => $this->querySuffixes(),
            'agentBranches' => $this->queryBranches(),
            'agentServiceDepartments' => $this->queryServiceDepartments(),
            'agentBUDepartments' => $this->BUDepartments,
            'agentTeams' => $this->teams,
        ]);
    }
}
