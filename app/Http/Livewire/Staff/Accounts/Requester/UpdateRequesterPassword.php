<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Models\User;
use Exception;
use Livewire\Component;

class UpdateRequesterPassword extends Component
{
    public User $user;
    public $new_password, $confirm_password;

    public function rules(): array
    {
        return (new UpdatePasswordRequest())->rules();
    }

    public function clearFormFields(): void
    {
        $this->resetValidation();
        $this->reset('new_password', 'confirm_password');
    }

    private function actionOnSubmit(): void
    {
        sleep(1);
        $this->clearFormFields();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function updatePassword(User $user): void
    {
        $this->validate();

        try {
            $user->update(['user' => $this->new_password]);
            $this->actionOnSubmit();
            flash()->addSuccess('Password has been updated.');

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.requester.update-requester-password');
    }
}
