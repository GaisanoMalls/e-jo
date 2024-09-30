<?php

namespace App\Http\Livewire\Approver\Notification;

use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationList extends Component
{
    protected $listeners = ['approverLoadNotificationList' => '$refresh'];

    private function triggerEvents()
    {
        $events = [
            'approverLoadNotificationList',
            'approverLoadNotificationCanvas',
            'approverLoadNavlinkNotification',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    public function readNotification($notificationId)
    {
        try {
            DB::transaction(function () use ($notificationId) {
                $notification = auth()->user()->notifications->find($notificationId);
                (!$notification->read()) ? $notification->markAsRead() : null;

                if (auth()->user()->hasRole(Role::APPROVER)) {
                    $ticket = Ticket::findOrFail($notification->data['ticket']['id']);

                    if ($ticket->status_id != Status::VIEWED) {
                        $ticket->update(['status_id' => Status::VIEWED]);
                        ActivityLog::make(ticket_id: $ticket->id, description: 'seen the ticket');
                    }
                }

                $this->triggerEvents();

                redirect()->route('approver.ticket.view_ticket_details', $notification->data['ticket']['id']);
            });

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
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

        return view('livewire.approver.notification.notification-list', [
            'approverNotifications' => $notifications,
        ]);
    }
}
