<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Http\Requests\SysAdmin\Manage\Account\StoreAgenRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateAgent extends Component
{
    use BasicModelQueries, Utils;

    public $BUDepartments = [], $teams = [], $selectedTeams = [];
    public $first_name, $middle_name, $last_name, $suffix, $email, $branch, $bu_department, $service_department;

    public function rules()
    {
        return (new StoreAgenRequest())->rules();
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

    public function actionOnSubmit()
    {
        sleep(1);
        $this->reset();
        $this->resetValidation();
        $this->emit('loadAgentList');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function saveAgent()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $agent = User::create([
                    'branch_id' => $this->branch,
                    'department_id' => $this->bu_department,
                    'service_department_id' => $this->service_department,
                    'role_id' => Role::AGENT,
                    'email' => $this->email,
                    'password' => \Hash::make('agent'),
                ]);

                $fullname = $this->first_name . $this->middle_name ?? "" . $this->last_name;

                Profile::create([
                    'user_id' => $agent->id,
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'slug' => $this->slugify($fullname)
                ]);

                $agent->teams()->attach($this->selectedTeams);
            });

            $this->actionOnSubmit();

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong.');
        }
    }

    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        return view('livewire.staff.accounts.agent.create-agent', [
            'agentSuffixes' => $this->querySuffixes(),
            'agentBranches' => $this->queryBranches(),
            'agentServiceDepartments' => $this->queryServiceDepartments(),
        ]);
    }
}
