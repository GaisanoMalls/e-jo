<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UpdateAgent extends Component
{
    use BasicModelQueries, Utils;

    public User $agent;
    public $BUDepartments = [];
    public $teams = [];
    public $currentTeams = [];
    public $selectedTeams = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $email;
    public $branch;
    public $bu_department;
    public $service_department;

    public function mount(User $agent)
    {
        $this->agent = $agent;
        $this->first_name = $agent->profile->first_name;
        $this->middle_name = $agent->profile->middle_name;
        $this->last_name = $agent->profile->last_name;
        $this->suffix = $agent->profile->suffix;
        $this->email = $agent->email;
        $this->branch = $agent->branches->pluck('id');
        $this->bu_department = $agent->buDepartments->pluck('id')->first();
        $this->service_department = $agent->serviceDepartments->pluck('id');
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
            'email' => "required|max:80|unique:users,email,{$this->agent->id}",
        ];
    }

    public function messages()
    {
        return [
            'selectedTeams.required' => 'Teams field is required.',
        ];
    }

    public function updatedBranch()
    {
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->teams = Team::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->dispatchBrowserEvent('get-branch-bu-departments-and-teams', [
            'BUDepartments' => $this->BUDepartments,
            'teams' => $this->teams,
        ]);
    }

    public function montain(string $name)
    {
        //
    }

    public function updateAgentAccount()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->agent->update(['email' => $this->email]);
                $this->agent->branches()->sync($this->branch);
                $this->agent->teams()->sync($this->selectedTeams);
                $this->agent->buDepartments()->sync($this->bu_department);
                $this->agent->serviceDepartments()->sync($this->service_department);

                $this->agent->profile()->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'slug' => $this->slugify(implode(" ", [
                        $this->first_name,
                        $this->middle_name,
                        $this->last_name,
                        $this->suffix,
                    ])),
                ]);

                flash()->addSuccess("You have successfully updated the account for {$this->agent->profile->getFullName()}.");
            });


        } catch (Exception $e) {
            dump($e->getMessage());
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
