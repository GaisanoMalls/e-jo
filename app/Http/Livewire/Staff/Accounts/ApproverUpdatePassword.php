<?php

namespace App\Http\Livewire\Staff\Accounts;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Http\Traits\SysAdmin\UserAccountConfig;
use App\Models\User;
use Livewire\Component;

class ApproverUpdatePassword extends Component
{
    use UserAccountConfig;

    public User $approver;
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

    public function updatePassword(User $approver)
    {
        $validatedData = $this->validate();

        try {
            $this->updateUserPassword($approver, $validatedData['new_password'], $validatedData['confirm_password']);
            $this->clearFormFields();
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Password has been updated.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.approver-update-password');
    }
}