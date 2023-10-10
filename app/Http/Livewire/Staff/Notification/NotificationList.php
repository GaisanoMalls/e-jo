<?php

namespace App\Http\Livewire\Staff\Notification;

use Livewire\Component;

class NotificationList extends Component
{
    protected $listeners = ['loadNotificationList' => '$refresh'];

    public function readNotification($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId)->markAsRead();
        $notifications = auth()->user()->notifications();
        // dd($notifications);

        $this->emit('loadNavlinkNotification');
        // return redirect()->route('staff.ticket.view_ticket', $notification->data);
    }

    public function render()
    {
        return view('livewire.staff.notification.notification-list', [
            'userNotifications' => auth()->user()->notifications()->latest()->get()
        ]);
    }
}