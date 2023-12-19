<?php

namespace App\Http\Livewire\Approver\Notification;

use Livewire\Component;

class UnreadNotificationCount extends Component
{
    protected $listeners = ['approverLoadUnreadNotificationCount' => '$refresh'];

    public function render()
    {
        return view('livewire.approver.notification.unread-notification-count');
    }
}
