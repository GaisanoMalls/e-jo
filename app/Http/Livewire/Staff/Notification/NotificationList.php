<?php

namespace App\Http\Livewire\Staff\Notification;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationList extends Component
{
    private ?Ticket $ticket = null;
    private ?string $notificationID = null;
    public array $disabledNotifications = [];

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

    private function isRequestersServiceDepartmentAdmin(Ticket $ticket)
    {
        return $ticket->withWhereHas('user', function ($requester) {
            $requester->withWhereHas('buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            });
        })->exists();
    }

    public function readNotification($notificationId)
    {
        try {
            DB::transaction(function () use ($notificationId) {
                $notification = auth()->user()->notifications->find($notificationId);
                (!$notification->read()) ? $notification->markAsRead() : null;

                if (auth()->user()->isServiceDepartmentAdmin()) {
                    $ticket = Ticket::findOrFail($notification->data['ticket']['id']);

                    if (
                        $ticket->status_id != Status::VIEWED
                        && $ticket->approval_status != ApprovalStatusEnum::APPROVED
                        && $this->isRequestersServiceDepartmentAdmin($ticket)
                        || !$ticket->whereDoesntHave('recommendations')
                    ) {
                        $ticket->update(['status_id' => Status::VIEWED]);
                        ActivityLog::make(ticket_id: $ticket->id, description: 'seen the ticket');
                    }
                }

                $this->triggerEvents();

                if (array_key_exists('forClarification', $notification->data) || array_key_exists('forSubtask', $notification->data)) {
                    if ($notification->data['forClarification']) {
                        return redirect()->route('staff.ticket.ticket_clarifications', $notification->data['ticket']['id']);
                    }
                    if ($notification->data['forSubtask']) {
                        return redirect()->route('staff.ticket.ticket_subtasks', $notification->data['ticket']['id']);
                    }
                } else {
                    return redirect()->route('staff.ticket.view_ticket', $notification->data['ticket']['id']);
                }
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