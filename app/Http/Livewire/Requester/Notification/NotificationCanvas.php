<?php

namespace App\Http\Livewire\Requester\Notification;

use Livewire\Component;

class NotificationCanvas extends Component
{
    protected $listeners = ['requesterLoadNotificationCanvas' => '$refresh'];

    /**
     * Marks all unread notifications as read and refreshes notification UI components.
     * 
     * Performs two main actions:
     * 1. Marks all unread notifications for the authenticated user as read
     * 2. Triggers UI updates by emitting events to:
     *    - Refresh the notification list (requesterLoadNotificationList)
     *    - Update navigation link notifications (requesterLoadNavlinkNotification)
     *    - Synchronize the unread count (requesterLoadUnreadNotificationCount)
     *
     * @return void
     * @fires requesterLoadNotificationList
     * @fires requesterLoadNavlinkNotification
     * @fires requesterLoadUnreadNotificationCount
     * @uses \Illuminate\Notifications\Notifiable::markAsRead Laravel's notification marking
     */
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

    /**
     * Clears all notifications for the authenticated user and refreshes the UI.
     *
     * Performs two main operations:
     * 1. Deletes all notification records for the current user
     * 2. Emits events to update all notification-related UI components:
     *    - Notification list (requesterLoadNotificationList)
     *    - Notification canvas/sidebar (requesterLoadNotificationCanvas)
     *    - Navigation link notifications (requesterLoadNavlinkNotification)
     *    - Unread notification counter (requesterLoadUnreadNotificationCount)
     *
     * Note: This action is irreversible and will permanently remove all notifications.
     *
     * @return void
     * @fires requesterLoadNotificationList
     * @fires requesterLoadNotificationCanvas
     * @fires requesterLoadNavlinkNotification
     * @fires requesterLoadUnreadNotificationCount
     * @uses \Illuminate\Notifications\Notifiable For notification handling
     */
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
