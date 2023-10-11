<?php

namespace App\Http\Livewire\Requester\Notification;

use Livewire\Component;

class UnreadNotificationCount extends Component
{
    protected $listeners = ['loadUnreadNotificationCount' => '$refresh'];

    public function render()
    {
        return view('livewire.requester.notification.unread-notification-count');
    }
}