<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Http\Traits\AppErrorLog;
use App\Models\User;
use Exception;
use Livewire\Component;

class UpdateApproverPassword extends Component
{
    public User $approver;
    public ?string $new_password = null;
    public ?string $confirm_password = null;

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

    public function updatePassword(User $approver)
    {
        $this->validate();

        try {
            $approver->update(['password' => $this->new_password]);
            $this->actionOnSubmit();
            noty()->addSuccess('Password has been updated.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.approver.update-approver-password');
    }
}
