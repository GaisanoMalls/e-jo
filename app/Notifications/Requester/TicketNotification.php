<?php

namespace App\Notifications\Requester;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class TicketNotification extends Notification implements ShouldQueue
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
        return [
            'ticket' => $this->ticket,
            'title' => $this->title,
            'message' => $this->message,
            'sender' => [
                'profilePicture' => Storage::url($this->ticket->user->profile->picture) ?? null,
                'nameInitial' => $this->ticket->user->profile->getNameInitial(),
                'fullName' => $this->ticket->user->profile->getFullName()
            ]
        ];
    }
}