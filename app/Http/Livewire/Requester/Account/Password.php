<?php

namespace App\Http\Livewire\Requester\Account;

use App\Http\Requests\Requester\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Password extends Component
{
    public $current_password, $new_password, $confirm_password;

    public function rules(): array
    {
        return (new UpdatePasswordRequest())->rules();
    }

    public function actionOnSubmit(): void
    {
        sleep(1);
        $this->reset();
        $this->resetValidation();
    }

    public function savePassword(): void
    {
        $this->validate();
        auth()->user()->update(['password' => Hash::make($this->new_password)]);
        $this->actionOnSubmit();
        flash()->addSuccess('Your password has been updated.');

    }

    public function render()
    {
        return view('livewire.requester.account.password');
    }
}
