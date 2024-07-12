<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Http\Requests\SysAdmin\Manage\Account\StoreApproverRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Level;
use App\Models\Profile;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class CreateApprover extends Component
{
    use BasicModelQueries, Utils;

    public array $bu_departments = [];
    public array $branches = [];
    public ?string $first_name = null;
    public ?string $middle_name = null;
    public ?string $last_name = null;
    public ?string $email = null;
    public ?string $suffix = null;
    public bool $asCostingApprover2 = false;

    public function rules()
    {
        return (new StoreApproverRequest())->rules();
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('loadApproverList');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function saveApprover()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $approver = User::create([
                    'email' => $this->email,
                    'password' => Hash::make('approver'),
                ]);

                $approver->assignRole(Role::APPROVER);
                $approver->buDepartments()->attach(array_map('intval', $this->bu_departments));
                $approver->branches()->attach(array_map('intval', $this->branches));
                $approver->givePermissionTo(
                    Permission::withWhereHas('roles', fn($role) => $role->where('roles.name', Role::APPROVER))->pluck('name')->toArray()
                );

                Profile::create([
                    'user_id' => $approver->id,
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

    public function render()
    {
        return view('livewire.staff.accounts.approver.create-approver', [
            'approverSuffixes' => $this->querySuffixes(),
            'approverBranches' => $this->queryBranches(),
            'approverBUDepartments' => $this->queryBUDepartments(),
        ]);
    }
}
