<?php

namespace App\Http\Livewire\Requester\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['requesterLoadNavlinkNotification' => '$refresh'];

    public function requesterShowNotifications()
    {
        $this->emit('requesterLoadNotificationList');
        $this->emit('requesterLoadNotificationCanvas');
        $this->emit('requesterLoadUnreadNotificationCount');
    }

    public function render()
    {
        return view('livewire.requester.notification.navlink-notification');
    }
}