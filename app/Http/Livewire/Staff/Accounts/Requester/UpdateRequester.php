<?php

namespace App\Http\Livewire\Staff\Accounts\Requester;

use App\Http\Traits\BasicModelQueries;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdateRequester extends Component
{
    use BasicModelQueries;

    public User $user;
    public $BUDepartments = [];
    public $first_name, $middle_name, $last_name, $suffix, $email, $branch, $bu_department;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->first_name = $user->profile->first_name;
        $this->middle_name = $user->profile->middle_name;
        $this->last_name = $user->profile->last_name;
        $this->suffix = $user->profile->suffix;
        $this->email = $user->email;
    }

    protected function rules()
    {
        return [
            'bu_department' => 'required',
            'branch' => 'required',
            'first_name' => 'required|min:2|max:100',
            'middle_name' => 'nullable|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'suffix' => 'nullable|min:1|max:4',
            'email' => 'required|max:80,email,' . $this->user->id
        ];
    }

    public function updateRequesterAccount()
    {
        $this->validate();
    }


    public function render()
    {
        return view('livewire.staff.accounts.requester.update-requester', [
            'requesterSuffixes' => $this->querySuffixes()
        ]);
    }
}
