<?php

namespace App\Http\Livewire\Staff\Notification;

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
    protected $listeners = ['staffLoadNotificationList' => '$refresh'];

    private function triggerEvents()
    {
        $events = [
            'staffLoadNotificationList',
            'staffLoadUnreadNotificationCount',
            'staffLoadNotificationCanvas',
            'staffLoadNavlinkNotification',
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

                if (auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN)) {
                    $ticket = Ticket::findOrFail($notification->data['ticket']['id']);

                    if ($ticket->status_id !== Status::VIEWED && ($ticket->status_id !== Status::APPROVED || $ticket->status_id !== Status::ON_PROCESS)) {
                        $ticket->update(['status_id' => Status::VIEWED]);
                        ActivityLog::make(ticket_id: $ticket->id, description: 'seen the ticket');
                    }
                }

                $this->triggerEvents();

                return (array_key_exists('for_clarification', $notification->data)) && $notification->data['for_clarification']
                    ? redirect()->route('staff.ticket.ticket_clarifications', $notification->data['ticket']['id'])
                    : redirect()->route('staff.ticket.view_ticket', $notification->data['ticket']['id']);
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

        return view('livewire.staff.notification.notification-list', [
            'userNotifications' => $notifications,
        ]);
    }
}