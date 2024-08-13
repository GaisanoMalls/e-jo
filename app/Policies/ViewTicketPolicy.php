<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ViewTicketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function canViewTicket(User $user, Ticket $ticket): bool
    {
        // return TicketApproval::where('ticket_id', $ticket->id)
        //     ->withWhereHas('helpTopicApprover', function ($approver) use ($user) {
        //         $approver->where('user_id', $user->id);
        //     })->exists();
    }
}
