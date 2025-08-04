<?php

namespace App\Http\Livewire;

use App\Http\Requests\AuthRequest;
use App\Http\Traits\AuthRedirect;
use App\Models\User;
use Livewire\Component;

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

        if (User::where('email', $this->email)->doesntExist()) {
            $this->addError('email', 'Email not found.');
        }

        if (auth()->attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => 1])) {
            session()->regenerate();
            return $this->redirectAuthenticatedWithRole();
        }

        $this->addError('password', 'Incorrect password. Please try again');
    }

    public function render()
    {
        return view('livewire.auth-user');
    }
}