<?php

namespace App\Http\Livewire\Approver\Notification;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class NotificationCanvas extends Component
{
    protected $listeners = ['approverLoadNotificationCanvas' => '$refresh'];

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->emit('approverLoadNotificationList');
        $this->emit('approverLoadNotificationCanvas');
        $this->emit('approverLoadNavlinkNotification');
        $this->emit('approverLoadUnreadNotificationCount');
    }

    public function clearNotifications()
    {
        auth()->user()->notifications->each(fn(Builder $notification) => $notification->delete());
        $this->emit('approverLoadNotificationList');
        $this->emit('approverLoadNotificationCanvas');
        $this->emit('approverLoadNavlinkNotification');
        $this->emit('approverLoadUnreadNotificationCount');
    }

    public function render()
    {
        $hasUnreadNotifications = false;
        if (auth()->user()->unreadNotifications->count() > 0) {
            $hasUnreadNotifications = true;
        }

        return view('livewire.approver.notification.notification-canvas', [
            'hasUnreadNotifications' => $hasUnreadNotifications,
        ]);
    }
}
