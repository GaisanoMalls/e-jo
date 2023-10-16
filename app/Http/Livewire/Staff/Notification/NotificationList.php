<?php

namespace App\Http\Livewire\Staff\Notification;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
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
                $notification->markAsRead();

                if (auth()->user()->role_id === Role::SERVICE_DEPARTMENT_ADMIN) {
                    $ticket = Ticket::findOrFail($notification->data['ticket']['id']);
                    if ($ticket->status_id != Status::VIEWED) {
                        $ticket->update(['status_id' => Status::VIEWED]);
                        ActivityLog::make($ticket->id, 'seen the ticket');
                    }
                }

                $this->emit('loadNotificationCanvas');
                $this->emit('loadNavlinkNotification');
                return redirect()->route('staff.ticket.view_ticket', $notification->data['ticket']['id']);

            });
        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications->find($notificationId)->delete();
        $this->emit('loadNotificationCanvas');
        $this->emit('loadNavlinkNotification');
    }

    public function render()
    {
        return view('livewire.staff.notification.notification-list', [
            'userNotifications' => auth()->user()->notifications()->latest()->get()
        ]);
    }
}