<?php

namespace App\Http\Livewire\Approver\Notification;

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
                        ActivityLog::make($ticket->id, 'seen the ticket');
                    }
                }

                $this->emit('approverLoadNotificationCanvas');
                $this->emit('approverLoadNavlinkNotification');

                redirect()->route('approver.ticket.view_ticket_details', $notification->data['ticket']['id']);
            });

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications->find($notificationId)->delete();
        $this->emit('approverLoadNotificationCanvas');
        $this->emit('approverLoadNavlinkNotification');
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
