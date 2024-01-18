<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UpdateAgentPassword extends Component
{
    public User $agent;
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

    public function updatePassword(User $agent)
    {
        $this->validate();

        try {
            $agent->update(['password' => Hash::make($this->new_password)]);
            $this->actionOnSubmit();
            noty()->addSuccess('Password has been updated.');

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.agent.update-agent-password');
    }
}
