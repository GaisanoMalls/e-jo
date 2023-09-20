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
    public $hasEmptyFields = false;

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


    public function checkEmptyFields()
    {
        (empty($this->email) || empty($this->password))
            ? $this->hasEmptyFields = true
            : $this->hasEmptyFields = false;
    }

    public function login()
    {
        $validatedData = $this->validate();

        if (
            Auth::attempt([
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'is_active' => true
            ])
        ) {
            session()->regenerate();
            return $this->redirectAuthenticatedWithRole();
        }

        $this->reset('password');
        session()->flash('error', 'Invalid email or password. Please try again.');

    }

    public function render()
    {
        $this->checkEmptyFields();
        return view('livewire.auth-user');
    }
}