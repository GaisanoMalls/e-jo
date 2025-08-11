<?php

namespace App\Http\Livewire\Auth;

use App\Http\Requests\Requester\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ForceChangePassword extends Component
{
    public ?string $current_password = null;
    public ?string $new_password = null;
    public ?string $confirm_password = null;

    public function rules()
    {
        return (new UpdatePasswordRequest())->rules();
    }

    private function resetForm()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();
        /** @var User $user */
        $user = auth()->user();
        $user->update(['password' => Hash::make($this->new_password)]);
        // Touch the updated_at so user is no longer forced
        $user->touch();
        $this->resetForm();
        noty()->addSuccess('Your password has been updated.');
        return redirect()->intended('/');
    }

    public function render()
    {
        return view('livewire.auth.force-change-password');
    }
}


