<?php

namespace App\Http\Livewire\Approver;

use Illuminate\Support\Collection;
use Livewire\Component;
use App\Http\Traits\Approver\Tickets as ApproverTickets;

class Dashboard extends Component
{
    use ApproverTickets;

    public Collection $openTickets;
    public Collection $viewedTickets;
    public Collection $approvedTickets;
    public Collection $disapprovedTickets;
    public Collection $onProcessTickets;

    public function mount()
    {
        $this->openTickets = $this->getOpenTickets();
        $this->viewedTickets = $this->getViewedTickets();
        $this->approvedTickets = $this->getApprovedTickets();
        $this->disapprovedTickets = $this->getDisapprovedTickets();
        $this->onProcessTickets = $this->getOnProcessTickets();
    }


    public function render()
    {
        return view('livewire.approver.dashboard');
    }
}