<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class ApproverList extends Component
{
    use Utils;

    public $allPermissions;
    public $approvers;
    public $approverDeleteId;
    public $approverAssignPermissionId;
    public $approverFullName;
    public $approverPermissions = [];

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
        $this->approverFullName = $approver->profile->getFullName();
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
            noty()->addSuccess('Approver account has been deleted');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    private function getInitialQuery()
    {
        return $this->approvers = User::role(Role::APPROVER)->orderByDesc('created_at')->get();
    }

    public function render()
    {
        $this->approvers = (Route::is('staff.manage.user_account.index'))
            ? User::with(['profile'])->role(Role::APPROVER)->take(5)->orderByDesc('created_at')->get()
            : (
                (Route::is('staff.manage.user_account.approvers'))
                ? User::with(['profile'])->role(Role::APPROVER)->orderByDesc('created_at')->get()
                : $this->getInitialQuery()
            );

        return view('livewire.staff.accounts.approver.approver-list', [
            'approvers' => $this->approvers,
            'allPermissions' => $this->getAllPermissions(),
        ]);
    }
}
