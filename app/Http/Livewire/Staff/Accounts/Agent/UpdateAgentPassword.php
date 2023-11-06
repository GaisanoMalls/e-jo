<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdateAgentPassword extends Component
{
    public User $agent;
    public $new_password, $confirm_password;

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

    public function updatePassword(User $agent)
    {
        $this->validate();

        try {
            $agent->update(['password' => Hash::make($this->new_password)]);
            $this->actionOnSubmit();
            flash()->addSuccess('Password has been updated.');

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.agent.update-agent-password');
    }
}
