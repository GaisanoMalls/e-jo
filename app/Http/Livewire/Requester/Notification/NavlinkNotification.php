<?php

namespace App\Http\Livewire\Requester\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['requesterLoadNavlinkNotification' => '$refresh'];

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

    public function requesterShowNotifications()
    {
        $this->triggerEvents();
    }

    public function render()
    {
        return view('livewire.requester.notification.navlink-notification');
    }
}