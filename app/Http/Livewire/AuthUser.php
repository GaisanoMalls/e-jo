<?php

namespace App\Http\Livewire;

use App\Http\Requests\AuthRequest;
use App\Http\Traits\AuthRedirect;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AuthUser extends Component
{
    use AuthRedirect;

    public string $email = "";
    public string $password = "";

    protected function rules()
    {
        return (new AuthRequest())->rules();
    }

    protected function messages()
    {
        return (new AuthRequest())->messages();
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function login()
    {
        $this->validate();

        if (
            Auth::attempt([
                'email' => $this->email,
                'password' => $this->password,
                'is_active' => true
            ])
        ) {
            session()->regenerate();
            sleep(1);
            return $this->redirectAuthenticatedWithRole();
        }

        $this->reset('password');
        session()->flash('error', 'Invalid email or password. Please try again.');

    }

    public function render()
    {
        return view('livewire.auth-user');
    }
}