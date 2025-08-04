<?php

namespace App\Http\Livewire\Auth;

use App\Http\Requests\SysAdmin\Manage\Account\StoreUserRequest;
use Livewire\Component;
use App\Models\Suffix;
use App\Models\Branch;
use App\Models\Department;
use App\Models\DepartmentBranch; // Assuming the pivot model name is DepartmentBranch
use App\Http\Traits\AppErrorLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use App\Models\Profile;
use App\Models\Role;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\UserApproval;
use App\Notifications\UserApprovalNotification;
use Illuminate\Support\Facades\Notification;

class CreateAccount extends Component
{
    use BasicModelQueries, Utils;

    public $first_name, $middle_name, $last_name, $suffix, $email, $branch, $department, $approver;
    public $BUDepartments = [];
    public $approvers = []; // Holds the available approvers based on branch and department

    // This function will load departments based on the selected branch
    public function loadDepartments()
    {
        if ($this->branch) {
            // Fetch departments associated with the selected branch
            $this->BUDepartments = DepartmentBranch::where('branch_id', $this->branch)
                ->with('department') // Make sure the department is loaded
                ->get()
                ->pluck('department') // Get only the departments
                ->toArray();
        } else {
            $this->BUDepartments = [];
        }

        // Reset approvers when branch changes
        $this->approvers = [];
        $this->approver = null;
    }

    // This function will load approvers based on selected branch and department
    public function loadApprovers()
    {
        if ($this->branch && $this->department) {
            $this->approvers = User::with('profile')
                ->role(Role::APPROVER) // Assuming the role is set up correctly with Spatie
                ->whereHas('branches', fn($q) => $q->where('branches.id', $this->branch))
                ->whereHas('buDepartments', fn($q) => $q->where('departments.id', $this->department))
                ->orderByDesc('created_at')
                ->get();
        } else {
            $this->approvers = [];
        }
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('loadRequesterList');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                // Create user
                $user = User::create([
                    'email' => $this->email,
                    'password' => Hash::make('requester'),
                    'is_active' => 0,
                ]);

                // Assign role and permissions
                $user->assignRole(Role::USER);
                $user->branches()->attach($this->branch);
                $user->buDepartments()->attach($this->department);
                $user->givePermissionTo(
                    Permission::withWhereHas(
                        'roles',
                        fn($role) => $role->where('roles.name', Role::USER)
                    )->pluck('name')->toArray()
                );

                // Create profile
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

                UserApproval::create([
                    'user_id' => $user->id,
                    'approver_id' => $this->approver, // coming from selected form value
                    'is_approved' => false,
                    'date_approved' => null,
                ]);

                $specificApprover = User::find($this->approver); // or however you store it

                Notification::send(
                    $specificApprover, // the notifiable (must be a User model)
                    new UserApprovalNotification($user, 'Title', 'Message')
                );

                $this->actionOnSubmit();
                noty()->addSuccess('Successfully created an account and is need for approval. You will notify once confirmed.');
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function rules()
    {
        return (new StoreUserRequest())->rules();
    }

    public function render()
    {
        return view('livewire.auth.create-account', [
            'requesterSuffixes' => Suffix::all(),
            'requesterBranches' => Branch::all(),
            'BUDepartments' => $this->BUDepartments,
            'approvers' => $this->approvers,  // Pass approvers to the view
        ]);
    }
}
