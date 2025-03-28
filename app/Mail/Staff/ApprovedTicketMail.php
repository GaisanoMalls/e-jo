<?php

namespace App\Mail\Staff;

use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovedTicketMail extends Mailable implements ShouldQueue
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
        // Get the current approver who is responsible for approving the ticket.
        $approver = User::role([Role::SERVICE_DEPARTMENT_ADMIN, Role::APPROVER])
            ->with('profile')
            ->find(auth()->user()->id);

        return new Content(
            markdown: 'mail.staff.approved-ticket-mail',
            with: [
                'headerGreeting' => "Good Day {$this->recipient->profile->getFullName}",
                'message' => "This is to inform you that the approver {$approver->profile->getFullName} has approved the requested ticket created by {$this->ticket->user->profile->getFullName}",
                'subject' => $this->ticket->subject,
                'branch' => $this->ticket->user->getBranches(),
                'department' => $this->ticket->user->getBUDepartments(),
                'dateCreated' => $this->ticket->dateCreated(),
                'ticketURL' => env('APP_URL') . "/staff/ticket/{$this->ticket->id}/view",
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
