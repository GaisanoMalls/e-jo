<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Http\Traits\SysAdmin\UserAccountConfig;
use App\Models\User;
use Livewire\Component;

class UpdateRequesterPassword extends Component
{
    use UserAccountConfig;

    public User $user;
    public $new_password, $confirm_password;

    protected function rules()
    {
        return (new UpdatePasswordRequest())->rules();
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function clearFormFields()
    {
        $this->resetValidation();
        $this->reset('new_password', 'confirm_password');
    }

    public function actionOnSubmit()
    {
        sleep(1);
        $this->clearFormFields();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function updatePassword(User $user)
    {
        $this->validate();

        try {
            $this->updateUserPassword($user, $this->new_password, $this->confirm_password);
            $this->actionOnSubmit();
            flash()->addSuccess('Password has been updated.');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.requester.update-requester-password');
    }
}