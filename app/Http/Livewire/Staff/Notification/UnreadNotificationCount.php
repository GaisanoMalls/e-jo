<?php

namespace App\Http\Livewire\Staff\Notification;

use Livewire\Component;

class UnreadNotificationCount extends Component
{
    protected $listeners = ['loadUnreadNotificationCount' => '$refresh'];

    public function render()
    {
        return view('livewire.staff.notification.unread-notification-count');
    }
}