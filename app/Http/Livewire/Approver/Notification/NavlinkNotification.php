<?php

namespace App\Http\Livewire\Approver\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['approverLoadNotificationCanvas' => '$refresh'];

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
