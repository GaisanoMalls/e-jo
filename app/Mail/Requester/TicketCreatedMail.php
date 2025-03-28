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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Ticket $ticket, public User $recipient)
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
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
            to: [new Address($this->recipient->email, $this->recipient->profile->getFullName)],
            subject: "New Ticket Created - {$this->ticket->ticket_number}",
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
                'headerGreeting' => "Good Day {$this->recipient->profile->getFullName}",
                'message' => "This is to inform you that a new ticket has been created by {$this->ticket->user->profile->getFullName}, with a ticket number of **{$this->ticket->ticket_number}**.",
                'subject' => $this->ticket->subject,
                'branch' => $this->ticket->user->getBranches(),
                'department' => $this->ticket->user->getBUDepartments(),
                'dateCreated' => $this->ticket->dateCreated(),
                'ticketURL' => env('APP_URL') . "/staff/ticket/{$this->ticket->id}/view" // Using Herd
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
