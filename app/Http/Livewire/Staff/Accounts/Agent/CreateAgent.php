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
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateAgent extends Component
{
    use BasicModelQueries, Utils;

    public $BUDepartments = [];
    public $teams = [];
    public $selectedTeams = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $email;
    public $branch;
    public $bu_department;
    public $service_department;

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
            'teams' => $this->teams,
        ]);
    }

    public function actionOnSubmit()
    {
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
                    'email' => $this->email,
                    'password' => Hash::make('agent'),
                ]);
                $agent->assignRole(Role::AGENT);
                $agent->branches()->attach($this->branch);
                $agent->buDepartments()->attach($this->bu_department);
                $agent->serviceDepartments()->attach($this->service_department);

                $fullname = $this->first_name . $this->middle_name ?? "" . $this->last_name;
                Profile::create([
                    'user_id' => $agent->id,
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'slug' => $this->slugify($fullname),
                ]);

                $agent->teams()->attach($this->selectedTeams);
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong.');
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
