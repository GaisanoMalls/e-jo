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
    // public bool $notifReadByCoSDA = false;

    protected $listeners = ['staffLoadNotificationList' => '$refresh'];

    // public function boot()
    // {
    //     $this->syncReadNotifications();
    // }

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

                    if (
                        !$notification->read()
                        && $ticket->approval_status !== ApprovalStatusEnum::APPROVED
                        && $ticket->status_id !== Status::VIEWED
                        && ($ticket->status_id !== Status::APPROVED || $ticket->status_id !== Status::ON_PROCESS)
                    ) {
                        $ticket->update(['status_id' => Status::VIEWED]);
                        ActivityLog::make(ticket_id: $ticket->id, description: 'seen the ticket');
                    }
                }

                $this->triggerEvents();

                return (array_key_exists('forClarification', $notification->data)) && $notification->data['forClarification']
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

    // public function syncReadNotifications()
    // {
    //     $currentServiceDeptAdmin = auth()->user();
    //     $serviceDepartmentAdmins = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
    //         ->whereNot('id', auth()->user()->id)
    //         ->withWhereHas('buDepartments', function ($department) use ($currentServiceDeptAdmin) {
    //             $department->whereIn('departments.id', $currentServiceDeptAdmin->buDepartments->pluck('id')->toArray());
    //         })
    //         ->withWhereHas('branches', function ($branch) use ($currentServiceDeptAdmin) {
    //             $branch->where('branches.id', $currentServiceDeptAdmin->branches->pluck('id')->first());
    //         })->get();

    //     foreach ($serviceDepartmentAdmins as $serviceDepartmentAdmin) {
    //         foreach ($serviceDepartmentAdmin->notifications as $coSDANotification) {
    //             foreach ($currentServiceDeptAdmin->notifications as $currSDANotification) {
    //                 if ($coSDANotification->data['ticket']['id'] == $currSDANotification->data['ticket']['id']) {
    //                     $this->notifReadByCoSDA = true;
    //                 }
    //             }
    //         }
    //     }
    // }

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