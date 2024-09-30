<?php

namespace App\Http\Livewire\Approver\Notification;

use Livewire\Component;

class NotificationCanvas extends Component
{
    protected $listeners = ['approverLoadNotificationCanvas' => '$refresh'];

    private function triggerEvents()
    {
        $events = [
            'approverLoadNotificationList',
            'approverLoadNotificationCanvas',
            'approverLoadNavlinkNotification',
            'approverLoadUnreadNotificationCount',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->triggerEvents();
    }

    public function clearNotifications()
    {
        auth()->user()->notifications->each(fn($notification) => $notification->delete());
        $this->triggerEvents();
    }

    public function render()
    {
        $hasUnreadNotifications = false;
        if (auth()->user()->unreadNotifications->count() > 0) {
            $hasUnreadNotifications = true;
        }

        return view('livewire.approver.notification.notification-canvas', [
            'hasUnreadNotifications' => $hasUnreadNotifications,
        ]);
    }
}
