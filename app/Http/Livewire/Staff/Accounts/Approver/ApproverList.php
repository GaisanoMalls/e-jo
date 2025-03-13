<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class ApproverList extends Component
{
    use Utils, WithPagination;

    public ?Collection $allPermissions = null;
    public ?int $approverDeleteId = null;
    public ?int $approverAssignPermissionId = null;
    public ?string $approverFullName = null;
    public array $approverPermissions = [];
    public ?string $searchApprover = null;

    public function mount()
    {
        $this->allPermissions = $this->getAllPermissions();
    }

    protected $listeners = ['loadApproverList' => '$refresh'];

    private function getAllPermissions()
    {
        return $this->allPermissions = Permission::all();
    }

    private function actionOnSubmit()
    {
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteApprover(User $approver)
    {
        $this->approverDeleteId = $approver->id;
        $this->approverFullName = $approver->profile->getFullName;
    }

    public function delete()
    {
        try {
            User::where('id', $this->approverDeleteId)->delete();
            if ($this->hasCostingApprover1()) {
                SpecialProjectAmountApproval::whereNotNull('service_department_admin_approver')
                    ->update(['fpm_coo_approver' => null]);
            }
            if ($this->hasCostingApprover2() && !$this->hasCostingApprover1()) {
                SpecialProjectAmountApproval::whereJsonContains('fpm_coo_approver->approver_id', $this->approverDeleteId)->delete();
            }

            $this->approverDeleteId = null;
            $this->actionOnSubmit();
            noty()->addSuccess('Approver account has been deleted.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    private function getInitialQuery()
    {
        return User::whereHas('profile', function ($profile) {
            $profile->where('first_name', 'like', "%{$this->searchApprover}%")
                ->orWhere('middle_name', 'like', "%{$this->searchApprover}%")
                ->orWhere('last_name', 'like', "%{$this->searchApprover}%");
        })
            ->orWhereHas('branches', fn($branch) => $branch->where('name', 'like', "%{$this->searchApprover}%"))
            ->orWhereHas('buDepartments', fn($buDept) => $buDept->where('name', 'like', "%{$this->searchApprover}%"))
            ->role(Role::APPROVER)
            ->orderByDesc('created_at')
            ->paginate(25);
    }

    public function updatingSearchApprover()
    {
        $this->resetPage();
    }

    public function clearApproverSearch()
    {
        $this->searchApprover = '';
    }

    public function render()
    {
        $approvers = $this->getInitialQuery();

        if (Route::is('staff.manage.user_account.index')) {
            $approvers = User::with('profile')
                ->role(Role::APPROVER)
                ->orderByDesc('created_at')
                ->paginate(15);
        }

        if (Route::is('staff.manage.user_account.approvers')) {
            $approvers = $this->getInitialQuery();
        }

        return view('livewire.staff.accounts.approver.approver-list', [
            'approvers' => $approvers
        ]);
    }
}
