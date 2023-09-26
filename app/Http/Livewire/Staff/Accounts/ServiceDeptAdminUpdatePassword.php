<?php

namespace App\Http\Livewire\Staff\Accounts;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Http\Traits\SysAdmin\UserAccountConfig;
use App\Models\User;
use Livewire\Component;

class ServiceDeptAdminUpdatePassword extends Component
{
    use UserAccountConfig;

    public User $serviceDeptAdmin;
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
        $this->clearFormFields();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function updatePassword(User $serviceDeptAdmin)
    {
        $this->validate();

        try {
            $this->updateUserPassword($serviceDeptAdmin, $this->new_password, $this->confirm_password);
            $this->actionOnSubmit();
            flash()->addSuccess('Password has been updated.');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }
    public function render()
    {
        return view('livewire.staff.accounts.service-dept-admin-update-password');
    }
}