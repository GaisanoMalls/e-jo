<?php

namespace App\Http\Livewire\Requester\Notification;

use Livewire\Component;

class NotificationCanvas extends Component
{
    protected $listeners = ['requesterLoadNotificationCanvas' => '$refresh'];

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        $events = [
            'requesterLoadNotificationList',
            'requesterLoadNavlinkNotification',
            'requesterLoadUnreadNotificationCount'
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    public function clearNotifications()
    {
        auth()->user()->notifications->each(fn($notification) => $notification->delete());

        $events = [
            'requesterLoadNotificationList',
            'requesterLoadNotificationCanvas',
            'requesterLoadNavlinkNotification',
            'requesterLoadUnreadNotificationCount'
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
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
