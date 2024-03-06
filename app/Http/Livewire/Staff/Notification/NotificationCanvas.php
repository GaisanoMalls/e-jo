<?php

namespace App\Http\Livewire\Staff\Notification;

use Livewire\Component;

class NotificationCanvas extends Component
{
    protected $listeners = ['staffLoadNotificationCanvas' => '$refresh'];

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->emit('staffLoadNotificationList');
        $this->emit('staffLoadNotificationCanvas');
        $this->emit('staffLoadNavlinkNotification');
        $this->emit('staffLoadUnreadNotificationCount');
    }

    public function clearNotifications()
    {
        auth()->user()->notifications->each(fn($notification) => $notification->delete());
        $this->emit('staffLoadNotificationList');
        $this->emit('staffLoadNotificationCanvas');
        $this->emit('staffLoadNavlinkNotification');
        $this->emit('staffLoadUnreadNotificationCount');
    }

    public function render()
    {
        $hasUnreadNotifications = false;
        if (auth()->user()->unreadNotifications->count() > 0) {
            $hasUnreadNotifications = true;
        }

        return view('livewire.staff.notification.notification-canvas', [
            'hasUnreadNotifications' => $hasUnreadNotifications,
        ]);
    }
}