<?php

namespace App\Http\Livewire\Requester\Account;

use App\Http\Requests\Requester\UpdatePasswordRequest;
use App\Http\Traits\AuthUserAccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Password extends Component
{
    use AuthUserAccount;

    public $current_password, $new_password, $confirm_password;

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function rules()
    {
        return (new UpdatePasswordRequest())->rules();
    }

    public function savePassword()
    {
        $this->validate();

        User::where('id', auth()->user()->id)->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset();
        flash()->addSuccess('Your password has been updated.');

    }

    public function render()
    {
        return view('livewire.requester.account.password');
    }
}