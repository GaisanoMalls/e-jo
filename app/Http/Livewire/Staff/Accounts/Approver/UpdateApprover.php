<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class UpdateApprover extends Component
{
    use BasicModelQueries, Utils;

    public User $approver;
    public $bu_departments = [];
    public $branches = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $suffix;
    public $permissions = [];
    public $currentPermissions = [];
    public $asCostingApprover2 = false;

    public function mount()
    {
        $this->first_name = $this->approver->profile->first_name;
        $this->middle_name = $this->approver->profile->middle_name;
        $this->last_name = $this->approver->profile->last_name;
        $this->email = $this->approver->email;
        $this->suffix = $this->approver->profile->suffix;
        $this->branches = $this->approver->branches->pluck("id")->toArray();
        $this->bu_departments = $this->approver->buDepartments->pluck("id")->toArray();
        $this->currentPermissions = $this->approver->getDirectPermissions()->pluck('name')->toArray();
        $this->asCostingApprover2 = $this->isCostingApprover2();
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
            'permissions' => 'nullable',
            'email' => "required|max:80|unique:users,email,{$this->approver->id}"
        ];
    }

    private function isCostingApprover2()
    {
        return SpecialProjectAmountApproval::where('fpm_coo_approver->approver_id', $this->approver->id)->exists();
    }

    public function updatedUseDirectPermission()
    {
        $this->dispatchBrowserEvent('use-direct-permission', ['useDirectPermission' => $this->useDirectPermission]);
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
                        $this->suffix,
                    ])),
                ]);

                $this->approver->branches()->sync($this->branches);
                $this->approver->syncPermissions($this->permissions);
                $this->approver->buDepartments()->sync($this->bu_departments);
                $this->approver->syncPermissions($this->permissions);

                if ($this->asCostingApprover2) {
                    if (!$this->hasCostingApprover2()) {
                        if (SpecialProjectAmountApproval::whereNull('fpm_coo_approver')->exists()) {
                            SpecialProjectAmountApproval::whereNull('fpm_coo_approver')
                                ->update([
                                    'fpm_coo_approver' => [
                                        'approver_id' => $this->approver->id,
                                        'is_approved' => false,
                                        'date_approved' => null
                                    ]
                                ]);
                        } elseif (SpecialProjectAmountApproval::whereNull('service_department_admin_approver')->exists()) {
                            SpecialProjectAmountApproval::whereNull('service_department_admin_approver')
                                ->update([
                                    'fpm_coo_approver' => [
                                        'approver_id' => $this->approver->id,
                                        'is_approved' => false,
                                        'date_approved' => null
                                    ]
                                ]);
                        } else {
                            // If neither field is null, create a new record
                            SpecialProjectAmountApproval::create([
                                'fpm_coo_approver' => [
                                    'approver_id' => $this->approver->id,
                                    'is_approved' => false,
                                    'date_approved' => null
                                ]
                            ]);
                        }
                    } else {
                        noty()->warning('Costing approver 2 already assigned');
                    }
                } else {
                    if (SpecialProjectAmountApproval::whereJsonContains('fpm_coo_approver->approver_id', $this->approver->id)->exists()) {
                        if ($this->hasCostingApprover1()) {
                            SpecialProjectAmountApproval::whereNotNull('service_department_admin_approver')
                                ->update(['fpm_coo_approver' => null]);
                        }
                        if ($this->hasCostingApprover2() && !$this->hasCostingApprover1()) {
                            SpecialProjectAmountApproval::query()->delete();
                        }
                    }
                }

                noty()->addSuccess("You have successfully updated the account for {$this->approver->profile->getFullName()}.");
            });

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function currentUserAsCostingApprover2()
    {
        return SpecialProjectAmountApproval::whereNotNull('fpm_coo_approver')
            ->whereJsonContains('fpm_coo_approver->approver_id', $this->approver->id)
            ->exists();
    }

    public function render()
    {
        return view('livewire.staff.accounts.approver.update-approver', [
            'approverSuffixes' => $this->querySuffixes(),
            'approverBranches' => $this->queryBranches(),
            'approverBUDepartments' => $this->queryBUDepartments(),
            'currentUserAsCostingApprover2' => $this->currentUserAsCostingApprover2(),
            'hasCostingApprover2' => $this->hasCostingApprover2(),
            'allPermissions' => Permission::withWhereHas('roles', fn($role) => $role->where('roles.name', Role::APPROVER))->get(),
        ]);
    }
}
