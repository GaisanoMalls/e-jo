<?php

namespace App\Http\Livewire\Requester\Account;

use App\Http\Requests\Requester\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Password extends Component
{
    public ?string $current_password = null;
    public ?string $new_password = null;
    public ?string $confirm_password = null;

    public function rules()
    {
        return (new UpdatePasswordRequest())->rules();
    }

    /**
     * Resets form state and validation errors.
     * 
     * Performs cleanup actions after form submission by:
     * 1. Resetting all component properties to their initial state
     * 2. Clearing all validation error messages
     *
     * @return void
     */
    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
    }

    /**
     * Updates the authenticated user's password.
     * 
     * Handles password update workflow by:
     * 1. Validating the new password input
     * 2. Hashing and saving the new password
     * 3. Performing form cleanup
     * 4. Showing success notification
     *
     * @return void
     * @throws \Illuminate\Validation\ValidationException If password validation fails
     * @uses \Illuminate\Support\Facades\Hash For password hashing
     * @emits success notification Via noty()
     */
    public function savePassword()
    {
        $this->validate();
        auth()->user()->update(['password' => Hash::make($this->new_password)]);
        $this->actionOnSubmit();
        noty()->addSuccess('Your password has been updated.');
    }

    public function render()
    {
        return view('livewire.requester.account.password');
    }
}
