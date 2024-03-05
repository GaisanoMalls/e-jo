<?php

namespace App\Http\Livewire\Requester\Notification;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class NotificationList extends Component
{
    protected $listeners = ['requesterLoadNotificationList' => '$refresh'];

    public function readNotification($notificationId)
    {
        $notification = auth()->user()->notifications->find($notificationId);
        (!$notification->read()) ? $notification->markAsRead() : null;

        $this->emit('requesterLoadNotificationCanvas');
        $this->emit('requesterLoadNavlinkNotification');

        return(array_key_exists('for_clarification', $notification->data)) && $notification->data['for_clarification']
            ? redirect()->route('user.ticket.ticket_clarifications', $notification->data['ticket']['id'])
            : redirect()->route('user.ticket.view_ticket', $notification->data['ticket']['id']);
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications->find($notificationId)->delete();
        $this->emit('requesterLoadNotificationCanvas');
        $this->emit('requesterLoadNavlinkNotification');
    }

    public function render()
    {
        $notifications = auth()->user()->notifications->filter(
            fn(Builder $notification) => Ticket::where('id', data_get($notification->data, 'ticket.id'))->exists()
        );
        return view('livewire.requester.notification.notification-list', [
            'userNotifications' => $notifications,
        ]);
    }
}
