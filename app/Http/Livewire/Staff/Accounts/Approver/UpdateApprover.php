<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UpdateApprover extends Component
{
    use BasicModelQueries, Utils;

    public User $approver;
    public $bu_departments = [];
    public $branches = [];
    public $currentBranches = [];
    public $currentBUDepartments = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $suffix;

    public function mount(User $approver)
    {
        $this->approver = $approver;
        $this->first_name = $approver->profile->first_name;
        $this->middle_name = $approver->profile->middle_name;
        $this->last_name = $approver->profile->last_name;
        $this->email = $approver->email;
        $this->suffix = $approver->profile->suffix;
        $this->branches = $approver->branches->pluck("id")->toArray();
        $this->bu_departments = $approver->buDepartments->pluck("id")->toArray();
        $this->currentBranches = $approver->branches->pluck("id")->toArray();
        $this->currentBUDepartments = $approver->buDepartments->pluck("id")->toArray();
    }

    public function rules(): array
    {
        return [
            'branches' => 'required',
            'bu_departments' => 'required',
            'first_name' => 'required|min:2|max:100',
            'middle_name' => 'nullable|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'suffix' => 'nullable|min:1|max:4',
            'email' => "required|max:80|unique:users,email,{$this->approver->id}"
        ];
    }

    public function updateApproverAccount()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->approver->update(['email' => $this->email]);
                $this->approver->profile()->update([
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

                $this->approver->branches()->sync($this->branches);
                $this->approver->buDepartments()->sync($this->bu_departments);
            });

            sleep(1);
            flash()->addSuccess("You have successfully updated the account for {$this->approver->profile->getFullName()}.");

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Failed to update the account');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.approver.update-approver', [
            'approverSuffixes' => $this->querySuffixes(),
            'approverBranches' => $this->queryBranches(),
            'approverBUDepartments' => $this->queryBUDepartments(),
        ]);
    }
}
