<?php

namespace App\Http\Livewire\Staff\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['staffLoadNavlinkNotification' => '$refresh'];

    public function staffShowNotifications()
    {
        $this->emit('staffLoadNotificationList');
        $this->emit('staffLoadNotificationCanvas');
        $this->emit('staffLoadUnreadNotificationCount');
    }

    public function render()
    {
        return view('livewire.staff.notification.navlink-notification');
    }
}