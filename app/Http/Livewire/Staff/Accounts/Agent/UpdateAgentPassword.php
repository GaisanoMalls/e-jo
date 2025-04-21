<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Http\Traits\AppErrorLog;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdateAgentPassword extends Component
{
    public User $agent;
    public ?string $new_password = null;
    public ?string $confirm_password = null;

    public function rules()
    {
        return (new UpdatePasswordRequest())->rules();
    }

    /**
     * Clears the form fields and validation errors.
     *
     * This method resets any validation errors and clears the `new_password`
     * and `confirm_password` fields, ensuring the form is reset to its initial state.
     *
     * @return void
     */
    public function clearFormFields()
    {
        // Reset validation errors.
        $this->resetValidation();

        // Clear the new password and confirm password fields.
        $this->reset('new_password', 'confirm_password');
    }

    /**
     * Handles post-submission actions after updating the agent's password.
     *
     * This method performs the following actions:
     * 1. Clears the form fields and validation errors by calling `clearFormFields`.
     * 2. Dispatches a browser event to close the modal window.
     *
     * @return void
     */
    private function actionOnSubmit()
    {
        // Clear the form fields and validation errors.
        $this->clearFormFields();

        // Dispatch a browser event to close the modal window.
        $this->dispatchBrowserEvent('close-modal');
    }

    /**
     * Updates the password for the given user agent.
     * 
     * This method validates the input, hashes the new password, updates the user record,
     * performs post-submission actions, and displays a success notification.
     * If an error occurs during the process, it logs the exception.
     *
     * @param User $agent The user whose password will be updated
     * @return void
     */
    public function updatePassword(User $agent)
    {
        $this->validate();

        try {
            // Update the agent's password with the hashed version of the new password
            $agent->update(['password' => Hash::make($this->new_password)]);

            // Perform any additional actions required after password update
            $this->actionOnSubmit();

            // Display success notification to user
            noty()->addSuccess('Password has been updated.');

        } catch (Exception $e) {
            // Log any exceptions that occur during the password update process
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.agent.update-agent-password');
    }
}
