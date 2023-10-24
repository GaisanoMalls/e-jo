<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class ApproverList extends Component
{
    public $approverDeleteId, $approverFullName;

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
            User::find($this->approverDeleteId)->delete();
            $this->approverDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Approver account has been deleted');
            sleep(1);

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addSuccess('Oops, something went wrong');
        }
    }

    public function render()
    {
        $approvers = User::with('branch')
            ->whereHas('role', fn($approver) => $approver->where('role_id', Role::APPROVER))
            ->take(5)->orderBy('created_at', 'desc')->get();

        return view('livewire.staff.accounts.approver.approver-list', [
            'approvers' => $approvers
        ]);
    }
}
