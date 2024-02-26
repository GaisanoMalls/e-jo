<?php

namespace App\Http\Livewire\Requester\TicketStatus\Sort;

use Livewire\Component;

class SortApprovedTickets extends Component
{
    public bool $withCosting = false;
    public bool $withOutCosting = false;
    public $approvedTickets = [];

    protected $listeners = ['loadApprovedTickets' => ''];

    public function getTicketsWithCosting()
    {
        // 
    }

    public function render()
    {
        return view('livewire.requester.ticket-status.sort.sort-approved-tickets');
    }
}
