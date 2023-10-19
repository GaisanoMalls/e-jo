<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Http\Requests\SysAdmin\Manage\Account\StoreUserRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateRequester extends Component
{
    use BasicModelQueries, Utils;

    public $BUDepartments = [];
    public $first_name, $middle_name, $last_name, $email, $suffix, $branch, $department;

    public function rules()
    {
        return (new StoreUserRequest())->rules();
    }

    public function updatedBranch()
    {
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->dispatchBrowserEvent('get-branch-bu-departments', ['BUDepartments' => $this->BUDepartments]);
    }

    public function actionOnSubmit()
    {
        sleep(1);
        $this->reset();
        $this->resetValidation();
        $this->emit('loadRequesterList');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function saveRequester()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $user = User::create([
                    'department_id' => $this->department,
                    'branch_id' => $this->branch,
                    'role_id' => Role::USER,
                    'email' => $this->email,
                    'password' => \Hash::make('requester')
                ]);

                Profile::create([
                    'user_id' => $user->id,
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

            $this->actionOnSubmit();
            flash()->addSuccess('Account successfully created');

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.requester.create-requester', [
            'requesterBranches' => $this->queryBranches(),
            'requesterSuffixes' => $this->querySuffixes(),
        ]);
    }
}