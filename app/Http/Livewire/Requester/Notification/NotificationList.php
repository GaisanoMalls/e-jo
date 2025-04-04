<?php

namespace App\Http\Livewire\Requester\Notification;

use App\Models\Ticket;
use Livewire\Component;

class NotificationList extends Component
{
    protected $listeners = ['requesterLoadNotificationList' => '$refresh'];

    /**
     * Triggers notification UI refresh events.
     * 
     * Emits events to synchronize notification-related UI components:
     * 1. requesterLoadNotificationCanvas - Refreshes the notification sidebar/canvas
     * 2. requesterLoadNavlinkNotification - Updates notification indicators in navigation
     *
     * This ensures UI consistency after notification changes while minimizing
     * unnecessary component refreshes by only emitting essential events.
     *
     * @return void
     * @fires requesterLoadNotificationCanvas
     * @fires requesterLoadNavlinkNotification
     */
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

    /**
     * Marks a notification as read and redirects to the appropriate ticket view.
     *
     * Handles the complete notification interaction workflow:
     * 1. Finds the specified notification for the authenticated user
     * 2. Marks it as read if unread (idempotent operation)
     * 3. Triggers UI refresh events for notification components
     * 4. Redirects to either:
     *    - Ticket clarification page (for clarification notifications)
     *    - Standard ticket view (for regular notifications)
     *
     * @param string|int $notificationId The ID of the notification to mark as read
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If notification not found
     *
     * @fires triggerEvents To refresh notification UI components
     * @uses \Illuminate\Notifications\DatabaseNotification For notification handling
     */
    public function readNotification($notificationId)
    {
        $notification = auth()->user()->notifications->find($notificationId);
        (!$notification->read()) ? $notification->markAsRead() : null;

        $this->triggerEvents();

        return (array_key_exists('forClarification', $notification->data)) && $notification->data['forClarification'] === true
            ? redirect()->route('user.ticket.ticket_clarifications', $notification->data['ticket']['id'])
            : redirect()->route('user.ticket.view_ticket', $notification->data['ticket']['id']);
    }

    /**
     * Deletes a specific notification and refreshes the notification UI.
     * 
     * Performs two main operations:
     * 1. Finds and permanently deletes the specified notification for the authenticated user
     * 2. Triggers UI refresh events to update notification-related components
     *
     * @param string|int $notificationId The ID of the notification to delete
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If notification not found
     *
     * @fires triggerEvents To refresh notification UI components
     * @uses \Illuminate\Notifications\DatabaseNotification For notification handling
     */
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
