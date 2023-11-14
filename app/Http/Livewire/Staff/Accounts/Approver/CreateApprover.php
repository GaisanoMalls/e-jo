<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Http\Requests\SysAdmin\Manage\Account\StoreApproverRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateApprover extends Component
{
    use BasicModelQueries, Utils;

    public $bu_departments = [];
    public $branches = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $suffix;

    public function rules()
    {
        return (new StoreApproverRequest())->rules();
    }

    public function actionOnSubmit()
    {
        sleep(1);
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
                    'role_id' => Role::APPROVER,
                    'email' => $this->email,
                    'password' => \Hash::make('approver'),
                ]);

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
                        $this->suffix
                    ]))
                ]);

                $approver->branches()->attach($this->branches);
                $approver->buDepartments()->attach($this->bu_departments);
            });

            $this->actionOnSubmit();
            flash()->addSuccess('Account successfully created');

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Failed to save a new approver');
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
