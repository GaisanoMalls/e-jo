<?php

namespace App\Http\Livewire;

use App\Http\Requests\AuthRequest;
use App\Http\Traits\AuthRedirect;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AuthUser extends Component
{
    use AuthRedirect;

    public $email;
    public $password;

    public function rules()
    {
        return (new AuthRequest())->rules();
    }

    public function messages()
    {
        return (new AuthRequest())->messages();
    }

    public function updatedEmail()
    {
        if (User::where('email', $this->email)->doesntExist()) {
            $this->addError('email', 'Email not found.');
            return;
        }

        $this->resetValidation('email');
    }

    public function login()
    {
        $this->validate();
        sleep(1);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true])) {
            session()->regenerate();
            return $this->redirectAuthenticatedWithRole();
        }

        $this->reset('password');
        $this->addError('password', 'Incorrect password for this email.');
    }

    public function render()
    {
        return view('livewire.auth-user');
    }
}