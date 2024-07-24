<?php

namespace App\Mail\Requester;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Ticket $ticket;
    public User $recipient;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, User $recipient)
    {
        $this->ticket = $ticket;
        $this->recipient = $recipient;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(auth()->user()->email, auth()->user()->profile->getFullName),
            replyTo: [new Address($this->recipient->email, $this->recipient->profile->getFullName)],
            subject: "New Ticket - {$this->ticket->ticket_number}",
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.requester.ticket-created-mail',
            with: [
                'newTicketMessage' => "New Ticket - {$this->ticket->ticket_number}",
                'message' => "A new ticket has been created by {$this->ticket->user->profile->getFullName}",
                'url' => "http://10.10.99.81:8000/staff/ticket/{$this->ticket->id}/view"
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
