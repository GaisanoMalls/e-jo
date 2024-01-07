<?php

namespace App\Http\Livewire\Staff\Accounts\ServiceDepartmentAdmin;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Models\User;
use Exception;
use Livewire\Component;

class UpdateServiceDeptAdminPassword extends Component
{
    public User $serviceDeptAdmin;
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

    public function updatePassword(User $serviceDeptAdmin)
    {
        $this->validate();

        try {
            $serviceDeptAdmin->update(['password' => $this->new_password]);
            $this->actionOnSubmit();
            noty()->addSuccess('Password has been updated.');

        } catch (Exception $e) {
            dump($e->getMessage());
            noty()->addError('Oops, something went wrong');
        }
    }
    public function render()
    {
        return view('livewire.staff.accounts.service-department-admin.update-service-dept-admin-password');
    }
}
