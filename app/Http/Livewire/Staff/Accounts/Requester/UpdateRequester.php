<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class UpdateRequester extends Component
{
    use BasicModelQueries, Utils;

    public User $user;
    public $BUDepartments = [];
    public $currentPermissions = [];
    public $permissions = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $email;
    public $branch;
    public $bu_department;

    public function mount()
    {
        $this->first_name = $this->user->profile->first_name;
        $this->middle_name = $this->user->profile->middle_name;
        $this->last_name = $this->user->profile->last_name;
        $this->suffix = $this->user->profile->suffix;
        $this->email = $this->user->email;
        $this->branch = $this->user->branches->pluck('id')->first();
        $this->bu_department = $this->user->buDepartments->pluck('id')->first();
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->currentPermissions = $this->user->getDirectPermissions()->pluck('name')->toArray();
    }

    protected function rules()
    {
        return [
            'bu_department' => 'required',
            'branch' => 'required',
            'first_name' => 'required|min:2|max:100',
            'middle_name' => 'nullable|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'suffix' => 'nullable|min:1|max:4',
            'email' => "required|max:80|unique:users,email,{$this->user->id}",
        ];
    }

    public function updatedBranch()
    {
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->dispatchBrowserEvent('get-branch-bu-departments', ['BUDepartments' => $this->BUDepartments]);
    }

    public function updateRequesterAccount()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->user->update(['email' => $this->email]);
                $this->user->branches()->sync($this->branch);
                $this->user->buDepartments()->sync($this->bu_department);
                $this->user->syncPermissions($this->permissions);

                $this->user->profile()->update([
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
                noty()->addSuccess("You have successfully updated the account for {$this->user->profile->getFullName()}.");
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.requester.update-requester', [
            'requesterSuffixes' => $this->querySuffixes(),
            'requesterBranches' => $this->queryBranches(),
            'requesterBUDepartments' => $this->BUDepartments,
            'allPermissions' => Permission::withWhereHas('roles', fn($role) => $role->where('roles.name', Role::USER))->get()
        ]);
    }
}
