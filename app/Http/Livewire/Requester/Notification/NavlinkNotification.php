<?php

namespace App\Http\Livewire\Requester\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['requesterLoadNavlinkNotification' => '$refresh'];

    /**
     * Triggers multiple notification-related events for the requester.
     * 
     * Emits a series of events to refresh different notification UI components:
     * 1. requesterLoadNotificationList - Updates the main notification list
     * 2. requesterLoadNotificationCanvas - Refreshes the notification canvas/sidebar
     * 3. requesterLoadUnreadNotificationCount - Updates the unread notification counter
     *
     * This centralized event triggering ensures all notification-related UI elements
     * stay synchronized after important actions.
     *
     * @return void
     * @fires requesterLoadNotificationList
     * @fires requesterLoadNotificationCanvas
     * @fires requesterLoadUnreadNotificationCount
     */
    private function triggerEvents()
    {
        $events = [
            'requesterLoadNotificationList',
            'requesterLoadNotificationCanvas',
            'requesterLoadUnreadNotificationCount',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    /**
     * Displays requester notifications by triggering all notification-related events.
     * 
     * This is a convenience method that delegates to triggerEvents() to:
     * - Refresh the notification list
     * - Update the notification canvas
     * - Synchronize the unread count
     * 
     * Essentially provides a single entry point to update all notification UI components.
     *
     * @return void
     * @see triggerEvents() For the actual event emission implementation
     */
    public function requesterShowNotifications()
    {
        $this->triggerEvents();
    }

    public function render()
    {
        return view('livewire.requester.notification.navlink-notification');
    }
}