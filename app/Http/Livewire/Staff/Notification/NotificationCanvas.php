<?php

namespace App\Http\Livewire\Staff\Notification;

use Livewire\Component;

class NotificationCanvas extends Component
{
    public function markAllAsRead()
    {
        sleep(1);
        auth()->user()->unreadNotifications->markAsRead();
        $this->emit('loadNotificationList');
        $this->emit('loadNavlinkNotification');
        $this->emit('loadUnreadNotificationCount');
    }

    public function clearNotifications()
    {
        sleep(1);
        auth()->user()->notifications()->delete();
        $this->emit('loadNotificationList');
        $this->emit('loadNavlinkNotification');
        $this->emit('loadUnreadNotificationCount');
    }

    public function render()
    {
        return view('livewire.staff.notification.notification-canvas');
    }
}