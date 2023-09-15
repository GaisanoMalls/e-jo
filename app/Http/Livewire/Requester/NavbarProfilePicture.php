<?php

namespace App\Http\Livewire\Requester;

use Livewire\Component;

class NavbarProfilePicture extends Component
{
    protected $listeners = ["loadNavProfilePic" => "render"];

    public function render()
    {
        return view('livewire.requester.navbar-profile-picture');
    }
}