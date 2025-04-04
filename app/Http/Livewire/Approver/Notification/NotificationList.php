<?php

namespace App\Http\Livewire\Approver\Notification;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationList extends Component
{
    protected $listeners = ['approverLoadNotificationList' => '$refresh'];

    /**
     * Emits a series of predefined Livewire events to update notification-related components.
     *
     * This function broadcasts multiple events to ensure that the notification list,
     * notification canvas, and unread notification count are updated in real-time.
     * It iterates over a predefined list of event names and emits each event to its
     * corresponding Livewire listener.
     *
     * Events emitted:
     * - 'approverLoadNotificationList': Updates the notification list component.
     * - 'approverLoadNotificationCanvas': Updates the notification canvas component.
     * - 'approverLoadUnreadNotificationCount': Updates the unread notification count component.
     *
     * @return void
     */
    private function triggerEvents()
    {
        $events = [
            'approverLoadNotificationList', // Listener from NotificationList.php
            'approverLoadNotificationCanvas', // Listener from NotificationCanvas.php
            'approverLoadUnreadNotificationCount', // Listener from UnreadNotificationCount.php
        ];

        foreach ($events as $event) {
            // Iterates over the $events array
            // For each event in the array, the emit method is called to broadcast the event to the corresponding Livewire listeners.
            $this->emit($event);
        }
    }

    /**
     * Marks a specific notification as read and updates the associated ticket's status.
     *
     * This function retrieves a notification for the currently logged-in user using the given notification ID.
     * If the notification is unread, it marks it as read. If the user is an approver, it retrieves the associated
     * ticket using the ticket ID stored in the notification's data field. If the ticket's status is not yet VIEWED
     * and its approval status is not APPROVED, the ticket's status is updated to VIEWED, and an activity log is created.
     * Afterward, predefined Livewire events are triggered to update notification-related components, and the user
     * is redirected to the ticket details page.
     *
     * @param int $notificationId The ID of the notification to be marked as read.
     * @return void
     * @throws Exception If an error occurs during the database transaction or if the ticket does not exist.
     */
    public function readNotification($notificationId)
    {
        try {
            DB::transaction(function () use ($notificationId) {
                // The notification with the given $notificationId is retrieved for the currently logged-in user
                $notification = auth()->user()->notifications->find($notificationId);
                if (!$notification->read()) {
                    // Mark the notification as read if the notification is not already marked as read.
                    $notification->markAsRead();
                }

                // Checks if the currently logged-in user has the role of an approver
                if (auth()->user()->isApprover()) {
                    // The associated ticket is retrieved using the ticket id stored in the notification's data field.
                    // If the ticket does not exist, an exception is thrown
                    $ticket = Ticket::findOrFail($notification->data['ticket']['id']);

                    // Checks if the ticket's status is not yet VIEWED and its approval status is not APPROVED
                    if ($ticket->status_id != Status::VIEWED && $ticket->approval_status != ApprovalStatusEnum::APPROVED) {
                        // Update the ticket status to viewed
                        $ticket->update(['status_id' => Status::VIEWED]);
                        // Create a log of the viewed ticket.
                        ActivityLog::make(ticket_id: $ticket->id, description: 'seen the ticket');
                    }

                    // To emit Livewire events that update the notification list
                    $this->triggerEvents();
                    // Redirect the approver to the ticket details page for the associated ticket.
                    redirect()->route('approver.ticket.view_ticket_details', $notification->data['ticket']['id']);
                }
            });

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    /**
     * Deletes a specific notification for the currently logged-in user.
     *
     * This function retrieves a single notification for the currently logged-in user (approver)
     * using the given notification ID and deletes it from the database. After deleting the notification,
     * it triggers predefined Livewire events to update the notification list, notification canvas,
     * and unread notification count in real-time.
     *
     * @param int $notificationId The ID of the notification to be deleted.
     * @return void
     */
    public function deleteNotification($notificationId)
    {
        // Retrieve a single notification of the currently logged in user (approver) with the given id.
        auth()->user()->notifications->find($notificationId)->delete();
        // After deleting the notification, the triggerEvents method is called to emit Livewire events that update the notification list, notification canvas, and unread notification count.
        $this->triggerEvents();
    }

    public function render()
    {
        $notifications = auth()->user()->notifications->filter(
            fn($notification) => Ticket::where('id', data_get($notification->data, 'ticket.id'))->exists()
        );

        return view('livewire.approver.notification.notification-list', [
            'approverNotifications' => $notifications,
        ]);
    }
}
