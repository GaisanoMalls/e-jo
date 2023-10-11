<?php

namespace App\Notifications\ServiceDepartmentAdmin;

use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisapprovedTicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Ticket $ticket;
    public string $title;
    public string $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, string $title, string $message)
    {
        $this->ticket = $ticket;
        $this->title = $title;
        $this->message = $message;
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
        $serviceDepartmentAdmin = User::with('profile')->where('id', auth()->user()->id)
            ->whereHas('role', fn($query) => $query->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN))->first();

        return [
            'ticket' => $this->ticket,
            'title' => $this->title,
            'message' => $this->message,
            'sender' => [
                'profilePicture' => Storage::url($serviceDepartmentAdmin->profile->picture) ?? null,
                'nameInitial' => $serviceDepartmentAdmin->profile->getNameInitial(),
                'fullName' => $serviceDepartmentAdmin->profile->getFullName()
            ]
        ];
    }
}