<?php

namespace App\Http\Livewire\Requester\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['loadNavlinkNotification' => '$refresh'];

    public function render()
    {
        return view('livewire.requester.notification.navlink-notification');
    }
}