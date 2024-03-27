<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Http\Requests\SysAdmin\Manage\Account\StoreApproverRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\ApproverLevel;
use App\Models\Level;
use App\Models\Profile;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
    public $asCostingApprover2 = false;

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

                if (!$this->asCostingApprover2) {
                    ApproverLevel::create([
                        'user_id' => $approver->id,
                        'level_id' => Level::where('value', 2)->pluck('value')->first(),
                    ]);
                } else {
                    if (!$this->hasCostingApprover2()) {
                        if (SpecialProjectAmountApproval::whereNull('fpm_coo_approver')->exists()) {
                            SpecialProjectAmountApproval::whereNull('fpm_coo_approver')
                                ->update([
                                    'fpm_coo_approver' => [
                                        'approver_id' => $approver->id,
                                        'is_approved' => false,
                                        'date_approved' => null
                                    ]
                                ]);
                        } elseif (SpecialProjectAmountApproval::whereNull('service_department_admin_approver')->exists()) {
                            SpecialProjectAmountApproval::whereNull('service_department_admin_approver')
                                ->update([
                                    'fpm_coo_approver' => [
                                        'approver_id' => $approver->id,
                                        'is_approved' => false,
                                        'date_approved' => null
                                    ]
                                ]);
                        } else {
                            // If neither field is null, create a new record
                            SpecialProjectAmountApproval::create([
                                'fpm_coo_approver' => [
                                    'approver_id' => $approver->id,
                                    'is_approved' => false,
                                    'date_approved' => null
                                ]
                            ]);
                        }
                    } else {
                        noty()->warning('Costing approver 2 already assigned');
                    }
                }

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
            'hasCostingApprover2' => $this->hasCostingApprover2()
        ]);
    }
}
