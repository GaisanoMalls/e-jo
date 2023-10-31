<?php

namespace App\Http\Livewire\Staff\Notification;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationList extends Component
{
    protected $listeners = ['loadNotificationList' => '$refresh'];

    public function readNotification($notificationId)
    {
        try {
            DB::transaction(function () use ($notificationId) {
                $notification = auth()->user()->notifications->find($notificationId);
                (!$notification->read()) ? $notification->markAsRead() : null;

                if (auth()->user()->role_id === Role::SERVICE_DEPARTMENT_ADMIN) {
                    $ticket = Ticket::findOrFail($notification->data['ticket']['id']);
                    if ($ticket->status_id != Status::VIEWED) {
                        $ticket->update(['status_id' => Status::VIEWED]);
                        ActivityLog::make($ticket->id, 'seen the ticket');
                    }
                }

                $this->emit('loadNotificationCanvas');
                $this->emit('loadNavlinkNotification');

                return (array_key_exists('for_clarification', $notification->data)) && $notification->data['for_clarification']
                    ? redirect()->route('staff.ticket.ticket_clarifications', $notification->data['ticket']['id'])
                    : redirect()->route('staff.ticket.view_ticket', $notification->data['ticket']['id']);
            });

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function deleteNotification($notificationId): void
    {
        auth()->user()->notifications->find($notificationId)->delete();
        $this->emit('loadNotificationCanvas');
        $this->emit('loadNavlinkNotification');
    }

    public function render()
    {
        $notifications = auth()->user()->notifications->filter(
            fn($notification) => Ticket::where('id', data_get($notification->data, 'ticket.id'))->exists()
        );
        return view('livewire.staff.notification.notification-list', [
            'userNotifications' => $notifications
        ]);
    }
}