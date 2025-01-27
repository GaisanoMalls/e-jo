<?php

namespace App\Mail\Requester;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class RequesterClarificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Ticket $ticket, public User $recipient, public string $clarificationDescription)
    {
        $this->ticket = $ticket;
        $this->recipient = $recipient;
        $this->clarificationDescription = $clarificationDescription;
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
            to: [new Address($this->recipient->email, $this->recipient->profile->getFullName)],
            subject: "Ticket clarification - {$this->ticket->ticket_number}",
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
            markdown: 'mail.requester.from-requester-clarification-mail',
            with: [
                'ticketSubject' => "Requester's Clarification",
                'message' => "{$this->clarificationDescription}",
                'sender' => auth()->user()->profile->getFullName,
                'url' => "http://10.10.99.81:8000/staff/ticket/{$this->ticket->id}/clarifications",
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
        return [
            // Attachment::fromStorage($this->ticket->clarifications->fileAttachments)
        ];
    }
}
