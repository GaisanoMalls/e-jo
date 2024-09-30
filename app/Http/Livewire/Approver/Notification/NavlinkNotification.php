<?php

namespace App\Http\Livewire\Approver\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['approverLoadNotificationCanvas' => '$refresh'];

    private function triggerEvents()
    {
        $events = [
            'approverLoadNotificationList',
            'approverLoadNotificationCanvas',
            'approverLoadUnreadNotificationCount',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    public function approverShowNotifications()
    {
        $this->triggerEvents();
    }
    public function render()
    {
        return view('livewire.approver.notification.navlink-notification');
    }
}
