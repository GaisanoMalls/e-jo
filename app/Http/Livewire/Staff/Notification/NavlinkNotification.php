<?php

namespace App\Http\Livewire\Staff\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['loadNavlinkNotification' => '$refresh'];

    public function render()
    {
        return view('livewire.staff.notification.navlink-notification');
    }
}