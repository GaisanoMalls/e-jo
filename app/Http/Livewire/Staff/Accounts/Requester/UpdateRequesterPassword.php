<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Models\User;
use Exception;
use Livewire\Component;

class UpdateRequesterPassword extends Component
{
    public User $user;
    public $new_password;
    public $confirm_password;

    public function rules()
    {
        return (new UpdatePasswordRequest())->rules();
    }

    public function clearFormFields()
    {
        $this->resetValidation();
        $this->reset('new_password', 'confirm_password');
    }

    private function actionOnSubmit()
    {
        $this->clearFormFields();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function updatePassword(User $user)
    {
        $this->validate();

        try {
            $user->update(['user' => $this->new_password]);
            $this->actionOnSubmit();
            noty()->addSuccess('Password has been updated.');

        } catch (Exception $e) {
            dump($e->getMessage());
            noty()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.requester.update-requester-password');
    }
}
