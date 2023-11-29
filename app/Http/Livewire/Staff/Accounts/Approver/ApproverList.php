<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ApproverList extends Component
{
    public $approvers;
    public $approverDeleteId;
    public $approverFullName;

    protected $listeners = ['loadApproverList' => '$refresh'];

    public function deleteApprover(User $approver)
    {
        $this->approverDeleteId = $approver->id;
        $this->approverFullName = $approver->profile->getFullName();
        $this->dispatchBrowserEvent('show-delete-apprvoer-modal');
    }

    public function delete()
    {
        try {
            User::findOrFail($this->approverDeleteId)->delete();
            $this->approverDeleteId = null;
            sleep(1);
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Approver account has been deleted');

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addSuccess('Oops, something went wrong');
        }
    }

    private function getInitialQuery()
    {
        return $this->approvers = User::role(Role::APPROVER)
            ->orderByDesc('created_at')->get();
    }

    public function render()
    {
        $this->approvers = (Route::is('staff.manage.user_account.index'))
            ? User::with(['profile'])->role(Role::APPROVER)
                ->take(5)->orderByDesc('created_at')->get()
            : (
                (Route::is('staff.manage.user_account.approvers'))
                ? User::with(['profile'])->role(Role::APPROVER)
                    ->orderByDesc('created_at')->get()
                : $this->getInitialQuery()
            );

        return view('livewire.staff.accounts.approver.approver-list', [
            'approvers' => $this->approvers,
        ]);
    }
}
