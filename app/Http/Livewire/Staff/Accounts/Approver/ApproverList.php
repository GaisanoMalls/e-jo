<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ApproverList extends Component
{
    public $approvers;
    public $approverDeleteId, $approverFullName;

    protected $listeners = ['loadApproverList' => '$refresh'];

    public function deleteApprover(User $approver): void
    {
        $this->approverDeleteId = $approver->id;
        $this->approverFullName = $approver->profile->getFullName();
        $this->dispatchBrowserEvent('show-delete-apprvoer-modal');
    }

    public function delete(): void
    {
        try {
            User::find($this->approverDeleteId)->delete();
            $this->approverDeleteId = null;
            sleep(1);
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Approver account has been deleted');

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addSuccess('Oops, something went wrong');
        }
    }

    private function getInitialQuery(): Collection|array
    {
        return $this->approvers = User::with('branch')
            ->whereHas('role', fn($approver) => $approver->where('role_id', Role::APPROVER))
            ->orderByDesc('created_at')->get();
    }

    public function render()
    {
        $this->approvers = (Route::is('staff.manage.user_account.index'))
            ? User::with(['profile', 'branch'])
                ->whereHas('role', fn($approver) => $approver->where('role_id', Role::APPROVER))
                ->take(5)->orderByDesc('created_at')->get()
            : (
                (Route::is('staff.manage.user_account.approvers'))
                ? User::with(['profile', 'branch'])
                    ->whereHas('role', fn($approver) => $approver->where('role_id', Role::APPROVER))
                    ->orderByDesc('created_at')->get()
                : $this->getInitialQuery()
            );

        return view('livewire.staff.accounts.approver.approver-list', [
            'approvers' => $this->approvers
        ]);
    }
}
