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

class RequesterReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Ticket $ticket, public User $recipient, public string $message)
    {
        $this->ticket = $ticket;
        $this->recipient = $recipient;
        $this->message = $message;
    }


    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address(auth()->user()->email, auth()->user()->profile->getFullName),
            to: [new Address($this->recipient->email, $this->recipient->profile->getFullName)],
            subject: "Ticket reply - {$this->ticket->ticket_number}",
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'mail.staff.approver-clarification-mail',
            with: [
                'ticketSubject' => "Ticket Reply",
                'message' => "{$this->message}",
                'sender' => auth()->user()->profile->getFullName,
                'url' => "http://10.10.99.81:8000/staff/ticket/{$this->ticket->id}/view"
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
