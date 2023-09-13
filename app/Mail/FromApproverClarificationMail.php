<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FromApproverClarificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Ticket $ticket;
    public string $clarificationDescription;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, string $clarificationDescription)
    {
        $this->ticket = $ticket;
        $this->clarificationDescription = $clarificationDescription;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address(auth()->user()->email, auth()->user()->profile->getFullName()),
            subject: "Clarification for ticket {$this->ticket->ticket_number}",
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
            markdown: 'mail.approver-clarification-mail',
            with: [
                'ticketSubject' => "Approver's Clarification",
                'message' => "{$this->clarificationDescription}",
                'sender' => auth()->user()->profile->getFullName(),
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