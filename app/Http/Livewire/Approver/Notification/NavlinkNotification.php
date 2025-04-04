<?php

namespace App\Http\Livewire\Approver\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['approverLoadNotificationCanvas' => '$refresh'];

    /**
     * Emits a series of predefined Livewire events to update notification-related components.
     *
     * This function broadcasts multiple events to ensure that the notification list,
     * notification canvas, and unread notification count are updated in real-time.
     * It is typically called when a user interacts with the notification system.
     *
     * @return void
     */
    private function triggerEvents()
    {
        $events = [
            'approverLoadNotificationList', // Listener from NotificationList.php
            'approverLoadNotificationCanvas', // Listener from NotificationCanvas.php
            'approverLoadUnreadNotificationCount', // Listener from UnreadNotificationCount.php
        ];

        foreach ($events as $event) {
            // Iterates over the $events array
            // For each event in the array, the emit method is called to broadcast the event to the corresponding Livewire listeners.
            $this->emit($event);
        }
    }

    /**
     * Triggers events to update notification-related components.
     *
     * This function is called when the approver interacts with the notification system,
     * such as clicking a button to view notifications. It ensures that the notification list,
     * notification canvas, and unread notification count are updated in real-time by calling
     * the `triggerEvents` method.
     *
     * @return void
     */
    public function approverShowNotifications()
    {
        // To trigger the events when the button is clicked with this function.
        // This will load the following: notifications, total number of notifications
        $this->triggerEvents();
    }

    public function render()
    {
        return view('livewire.approver.notification.navlink-notification');
    }
}
