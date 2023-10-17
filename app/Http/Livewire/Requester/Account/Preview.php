<?php

namespace App\Http\Livewire\Requester\Account;

use Livewire\Component;

class Preview extends Component
{
    protected $listeners = ['loadProfilePreview' => '$refresh'];

    public function render()
    {
        return view('livewire.requester.account.preview');
    }
}