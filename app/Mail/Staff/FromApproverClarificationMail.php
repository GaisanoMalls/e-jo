<?php

namespace App\Mail\Staff;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FromApproverClarificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Ticket $ticket;
    public User $recipient;
    public string $clarificationDescription;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, User $recipient, string $clarificationDescription)
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
            replyTo: [new Address($this->recipient->email, $this->recipient->profile->getFullName)],
            subject: "Clarification for ticket {$this->ticket->ticket_number}",
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
            markdown: 'mail.staff.approver-clarification-mail',
            with: [
                'ticketSubject' => "Approver's Clarification",
                'message' => "{$this->clarificationDescription}",
                'sender' => auth()->user()->profile->getFullName,
                'url' => "http://10.10.99.81:8000/user/ticket/{$this->ticket->id}/view/clarifications"
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
