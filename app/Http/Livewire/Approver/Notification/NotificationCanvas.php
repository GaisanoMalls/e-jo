<?php

namespace App\Http\Livewire\Approver\Notification;

use Livewire\Component;

class NotificationCanvas extends Component
{
    protected $listeners = ['approverLoadNotificationCanvas' => '$refresh'];

    /**
     * Emits a series of predefined Livewire events to update notification-related components.
     *
     * This function broadcasts multiple events to ensure that the notification list,
     * notification canvas, and unread notification count are updated in real-time.
     * It iterates over a predefined list of event names and emits each event to its
     * corresponding Livewire listener.
     *
     * @return void
     */
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

    /**
     * Marks all unread notifications of the currently logged-in user as read.
     *
     * This function retrieves all unread notifications for the currently logged-in user
     * and marks them as read using the `markAsRead` method. After marking the notifications
     * as read, it triggers predefined Livewire events to update the notification list,
     * notification canvas, and unread notification count in real-time.
     *
     * @return void
     */
    public function markAllAsRead()
    {
        // Mark the unread notifications of the currently logged in user as read.
        auth()->user()->unreadNotifications->markAsRead();
        // Trigger the events to see the changes.
        $this->triggerEvents();
    }

    /**
     * Deletes all notifications of the currently logged-in user.
     *
     * This function retrieves all notifications for the currently logged-in user
     * and deletes them one by one using the `delete` method. After deleting the notifications,
     * it triggers predefined Livewire events to update the notification list,
     * notification canvas, and unread notification count in real-time.
     *
     * @return void
     */
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
