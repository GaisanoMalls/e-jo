<?php

namespace App\Http\Livewire\Requester\Notification;

use Livewire\Component;

class NotificationCanvas extends Component
{
    protected $listeners = ['requesterLoadNotificationCanvas' => '$refresh'];

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->emit('requesterLoadNotificationList');
        $this->emit('requesterLoadNavlinkNotification');
        $this->emit('requesterLoadUnreadNotificationCount');
    }

    public function clearNotifications()
    {
        auth()->user()->notifications->each(fn($notification) => $notification->delete());
        $this->emit('requesterLoadNotificationList');
        $this->emit('requesterLoadNotificationCanvas');
        $this->emit('requesterLoadNavlinkNotification');
        $this->emit('requesterLoadUnreadNotificationCount');
    }

    public function render()
    {
        $hasUnreadNotifications = false;
        if (auth()->user()->unreadNotifications->count() > 0) {
            $hasUnreadNotifications = true;
        }

        return view('livewire.requester.notification.notification-canvas', [
            'hasUnreadNotifications' => $hasUnreadNotifications,
        ]);
    }
}
