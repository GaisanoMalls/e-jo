<?php

namespace App\Http\Livewire\Requester\Notification;

use Livewire\Component;

class NotificationList extends Component
{
    protected $listeners = ['loadNotificationList' => '$refresh'];

    public function readNotification($notificationId)
    {
        auth()->user()->notifications->find($notificationId)->markAsRead();
        $this->emit('loadNavlinkNotification');
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications->find($notificationId)->delete();
        $this->emit('loadNotificationCanvas');
        $this->emit('loadNavlinkNotification');
    }

    public function render()
    {
        return view('livewire.requester.notification.notification-list', [
            'userNotifications' => auth()->user()->notifications()->latest()->get()
        ]);
    }
}