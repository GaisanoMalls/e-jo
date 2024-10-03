<?php

namespace App\Http\Livewire\Requester\Notification;

use App\Models\Ticket;
use Livewire\Component;

class NotificationList extends Component
{
    protected $listeners = ['requesterLoadNotificationList' => '$refresh'];

    private function triggerEvents()
    {
        $events = [
            'requesterLoadNotificationCanvas',
            'requesterLoadNavlinkNotification',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    public function readNotification($notificationId)
    {
        $notification = auth()->user()->notifications->find($notificationId);
        (!$notification->read()) ? $notification->markAsRead() : null;

        $this->triggerEvents();

        return (array_key_exists('forClarification', $notification->data)) && $notification->data['forClarification'] === true
            ? redirect()->route('user.ticket.ticket_clarifications', $notification->data['ticket']['id'])
            : redirect()->route('user.ticket.view_ticket', $notification->data['ticket']['id']);
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications->find($notificationId)->delete();
        $this->triggerEvents();
    }

    public function render()
    {
        $notifications = auth()->user()->notifications->filter(
            fn($notification) => Ticket::where('id', data_get($notification->data, 'ticket.id'))->exists()
        );
        return view('livewire.requester.notification.notification-list', [
            'userNotifications' => $notifications,
        ]);
    }
}
