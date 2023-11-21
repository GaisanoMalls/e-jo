<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Models\User;
use Exception;
use Livewire\Component;

class UpdateApproverPassword extends Component
{
    public User $approver;
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
        sleep(1);
        $this->clearFormFields();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function updatePassword(User $approver)
    {
        $this->validate();

        try {
            $approver->update(['password' => $this->new_password]);
            $this->actionOnSubmit();
            flash()->addSuccess('Password has been updated.');

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.approver.update-approver-password');
    }
}
