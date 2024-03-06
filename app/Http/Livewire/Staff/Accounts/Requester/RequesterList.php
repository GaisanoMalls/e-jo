<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Http\Traits\AppErrorLog;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class RequesterList extends Component
{
    public $users;
    public $requesterDeleteId;
    public $requesterFullName;

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
        return User::role(Role::USER)->orderByDesc('created_at')->get();
    }

    public function render()
    {
        $this->users = (Route::is('staff.manage.user_account.index'))
            ? User::with(['profile'])->role(Role::USER)
                ->take(5)->orderByDesc('created_at')->get()
            : (
                (Route::is('staff.manage.user_account.users'))
                ? User::with(['profile'])->role(Role::USER)
                    ->orderByDesc('created_at')->get()
                : $this->getInitialQuery()
            );

        return view('livewire.staff.accounts.requester.requester-list', [
            'users' => $this->users,
        ]);
    }
}
