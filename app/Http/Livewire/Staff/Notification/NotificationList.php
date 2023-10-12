<?php

namespace App\Http\Livewire\Staff\Notification;

use Livewire\Component;

class NotificationList extends Component
{
    protected $listeners = ['loadNotificationList' => '$refresh'];

    public function readNotification($notificationId)
    {
        $notification = auth()->user()->notifications->find($notificationId);
        $notification->markAsRead();
        $this->emit('loadNotificationCanvas');
        $this->emit('loadNavlinkNotification');
        return redirect()->route('staff.ticket.view_ticket', $notification->data['ticket']['id']);
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications->find($notificationId)->delete();
        $this->emit('loadNotificationCanvas');
        $this->emit('loadNavlinkNotification');
    }

    public function render()
    {
        return view('livewire.staff.notification.notification-list', [
            'userNotifications' => auth()->user()->notifications()->latest()->get()
        ]);
    }
}