<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class RequesterList extends Component
{
    public $requesterDeleteId, $requesterFullName;

    protected $listeners = ['loadRequesterList' => '$refresh'];

    public function deleteRequester(User $requester)
    {
        $this->requesterDeleteId = $requester->id;
        $this->requesterFullName = $requester->profile->getFullName();
        $this->dispatchBrowserEvent('show-delete-requester-modal');
    }

    public function delete()
    {
        try {
            User::find($this->requesterDeleteId)->delete();
            sleep(1);
            $this->requesterDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Requester account has been deleted');

        } catch (\Exception $e) {
            flash()->addSuccess('Oops, something went wrong');
        }
    }

    public function render()
    {
        $users = User::with(['department', 'branch'])
            ->whereHas('role', fn($user) => $user->where('role_id', Role::USER))
            ->take(5)->orderBy('created_at', 'desc')->get();

        return view('livewire.staff.accounts.requester.requester-list', [
            'users' => $users
        ]);
    }
}