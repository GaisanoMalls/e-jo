<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Http\Requests\SysAdmin\Manage\Account\StoreUserRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class CreateRequester extends Component
{
    use BasicModelQueries, Utils;

    public ?Collection $BUDepartments = null;
    public ?string $first_name = null;
    public ?string $middle_name = null;
    public ?string $last_name = null;
    public ?string $email = null;
    public ?string $suffix = null;
    public ?int $branch = null;
    public ?int $department = null;

    public function rules()
    {
        return (new StoreUserRequest())->rules();
    }

    public function updatedBranch()
    {
        $this->BUDepartments = Department::whereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->dispatchBrowserEvent('get-branch-bu-departments', ['BUDepartments' => $this->BUDepartments]);
    }

    private function actionOnSubmit()
    {
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
                    'email' => $this->email,
                    'password' => Hash::make('requester'),
                ]);

                $user->assignRole(Role::USER);
                $user->branches()->attach($this->branch);
                $user->buDepartments()->attach($this->department);
                $user->givePermissionTo(
                    Permission::withWhereHas(
                        'roles',
                        fn($role) => $role->where('roles.name', Role::USER)
                    )->pluck('name')->toArray()
                );

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
                        $this->suffix,
                    ])),
                ]);
                $this->actionOnSubmit();
                noty()->addSuccess('Account successfully created');
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
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
        return view('livewire.staff.accounts.requester.create-requester', [
            'requesterBranches' => $this->queryBranches(),
            'requesterSuffixes' => $this->querySuffixes(),
        ]);
    }
}
