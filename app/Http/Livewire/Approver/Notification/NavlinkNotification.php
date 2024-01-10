<?php

namespace App\Http\Livewire\Approver\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['approverLoadNotificationCanvas' => '$refresh'];

    public function approverShowNotifications()
    {
        $this->emit('approverLoadNotificationList');
        $this->emit('approverLoadNotificationCanvas');
        $this->emit('approverLoadUnreadNotificationCount');
    }
    public function render()
    {
        return view('livewire.approver.notification.navlink-notification');
    }
}
