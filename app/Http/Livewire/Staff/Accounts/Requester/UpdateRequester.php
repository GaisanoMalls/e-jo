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
    public $first_name, $middle_name, $last_name, $suffix, $email, $branch, $bu_department;

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->first_name = $user->profile->first_name;
        $this->middle_name = $user->profile->middle_name;
        $this->last_name = $user->profile->last_name;
        $this->suffix = $user->profile->suffix;
        $this->email = $user->email;
        $this->branch = $this->user->branch_id;
        $this->bu_department = $this->user->department_id;
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
    }

    protected function rules(): array
    {
        return [
            'bu_department' => 'required',
            'branch' => 'required',
            'first_name' => 'required|min:2|max:100',
            'middle_name' => 'nullable|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'suffix' => 'nullable|min:1|max:4',
            'email' => "required|max:80|unique:users,email,{$this->user->id}"
        ];
    }

    public function updatedBranch(): void
    {
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->dispatchBrowserEvent('get-branch-bu-departments', ['BUDepartments' => $this->BUDepartments]);
    }

    public function updateRequesterAccount(): void
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->user->update([
                    'branch_id' => $this->branch,
                    'department_id' => $this->bu_department,
                    'email' => $this->email
                ]);

                $this->user->profile()->update([
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
            });

            sleep(1);
            flash()->addSuccess("You have successfully updated the account for {$this->user->profile->getFullName()}.");

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Failed to update the account.');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.requester.update-requester', [
            'requesterSuffixes' => $this->querySuffixes(),
            'requesterBranches' => $this->queryBranches(),
            'requesterBUDepartments' => $this->BUDepartments
        ]);
    }
}
