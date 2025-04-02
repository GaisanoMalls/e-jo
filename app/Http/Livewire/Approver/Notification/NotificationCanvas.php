<?php

namespace App\Http\Livewire\Approver\Notification;

use Livewire\Component;

class NotificationCanvas extends Component
{
    protected $listeners = ['approverLoadNotificationCanvas' => '$refresh'];

    private function triggerEvents()
    {
        $events = [
            'approverLoadNotificationList', // Listener from NotificationList.php
            'approverLoadNotificationCanvas',// Listener from NotificationCanvas.php
            'approverLoadUnreadNotificationCount', // Listener from UnreadNotificationCount.php
        ];

        foreach ($events as $event) {
            // Iterates over the $events array
            // For each event in the array, the emit method is called to broadcast the event to the corresponding Livewire listeners.
            $this->emit($event);
        }
    }

    public function markAllAsRead()
    {
        // Mark the unread notifications of the currently logged in user as read.
        auth()->user()->unreadNotifications->markAsRead();
        // Trigger the events to see the changes.
        $this->triggerEvents();
    }

    public function clearNotifications()
    {
        // Delete all notifications of the currently logged in user.
        auth()->user()->notifications->each(fn($notification) => $notification->delete());
        // Trigger the events to see the changes.
        $this->triggerEvents();
    }

    public function render()
    {
        $hasUnreadNotifications = false;
        if (auth()->user()->unreadNotifications->count() > 0) {
            // To make the function markAllAsRead functional.
            // Otherwise, disable the functionlaitu of the markAllAsRead function
            $hasUnreadNotifications = true;
        }

        return view('livewire.approver.notification.notification-canvas', [
            'hasUnreadNotifications' => $hasUnreadNotifications,
        ]);
    }
}
