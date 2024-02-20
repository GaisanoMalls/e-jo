<?php

namespace App\Notifications\ServiceDepartmentAdmin;

use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApprovedTicketForRequesterNotification extends Notification
{
    use Queueable;

    public Ticket $ticket;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $serviceDepartmentAdmin = User::with('profile')->where('id', auth()->user()->id)->role(Role::SERVICE_DEPARTMENT_ADMIN)->first();

        return [
            'ticket' => $this->ticket,
            'title' => "Approved Ticket {$this->ticket->ticket_number}",
            'message' => "{$serviceDepartmentAdmin->profile->getFullName()} approved your ticket",
        ];
    }
}