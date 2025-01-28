<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Http\Traits\AppErrorLog;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class RequesterList extends Component
{
    use WithPagination;

    public $requesterDeleteId;
    public $requesterFullName;
    public $searchRequester = '';

    protected $listeners = ['loadRequesterList' => '$refresh'];

    public function deleteRequester(User $requester)
    {
        $this->requesterDeleteId = $requester->id;
        $this->requesterFullName = $requester->profile->getFullName;
        $this->dispatchBrowserEvent('show-delete-requester-modal');
    }

    public function delete()
    {
        try {
            User::where('id', $this->requesterDeleteId)->delete();
            $this->requesterDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess("Requester's account has been deleted");

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    private function getInitialQuery()
    {
        return User::whereHas('profile', function ($profile) {
            $profile->where('first_name', 'like', "%{$this->searchRequester}%")
                ->orWhere('middle_name', 'like', "%{$this->searchRequester}%")
                ->orWhere('last_name', 'like', "%{$this->searchRequester}%");
        })
            ->orWhereHas('branches', fn($branch) => $branch->where('name', 'like', "%{$this->searchRequester}%"))
            ->orWhereHas('buDepartments', fn($buDept) => $buDept->where('name', 'like', "%{$this->searchRequester}%"))
            ->role(Role::USER)
            ->orderByDesc('created_at')
            ->paginate(25);
    }

    public function updatingSearchRequester()
    {
        $this->resetPage();
    }

    public function clearRequesterSearch()
    {
        $this->searchRequester = '';
    }

    public function render()
    {
        $users = $this->getInitialQuery();

        if (Route::is('staff.manage.user_account.index')) {
            $users = User::with('profile')
                ->role(Role::USER)
                ->orderByDesc('created_at')
                ->paginate(15);
        }

        if (Route::is('staff.manage.user_account.users')) {
            $users = $this->getInitialQuery();
        }

        return view('livewire.staff.accounts.requester.requester-list', [
            'users' => $users,
        ]);
    }
}
