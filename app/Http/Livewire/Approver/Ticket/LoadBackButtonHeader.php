<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadBackButtonHeader extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadBackButtonHeader' => 'render'];

    public function render()
    {
        return view('livewire.approver.ticket.load-back-button-header');
    }
}