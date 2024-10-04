<?php

namespace App\Http\Livewire\Staff\Notification;

use Livewire\Component;

class NavlinkNotification extends Component
{
    protected $listeners = ['staffLoadNavlinkNotification' => '$refresh'];

    private function triggerEvents()
    {
        $events = [
            'staffLoadNotificationList',
            'staffLoadNotificationCanvas',
            'staffLoadUnreadNotificationCount',
            'loadSidebarCollapseTicketStatus'
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    public function staffShowNotifications()
    {
        $this->triggerEvents();
    }

    public function render()
    {
        return view('livewire.staff.notification.navlink-notification');
    }
}