<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UpdateRequester extends Component
{
    use BasicModelQueries, Utils;

    public User $user;
    public $BUDepartments = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $email;
    public $branch;
    public $bu_department;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->first_name = $user->profile->first_name;
        $this->middle_name = $user->profile->middle_name;
        $this->last_name = $user->profile->last_name;
        $this->suffix = $user->profile->suffix;
        $this->email = $user->email;
        $this->branch = $this->user->branches->pluck('id');
        $this->bu_department = $this->user->buDepartments->pluck('id');
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
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
                sleep(1);
                flash()->addSuccess("You have successfully updated the account for {$this->user->profile->getFullName()}.");
            });
        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Failed to update the account.');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.requester.update-requester', [
            'requesterSuffixes' => $this->querySuffixes(),
            'requesterBranches' => $this->queryBranches(),
            'requesterBUDepartments' => $this->BUDepartments,
        ]);
    }
}
