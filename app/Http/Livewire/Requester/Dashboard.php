<?php

namespace App\Http\Livewire\Requester;

use Illuminate\Support\Collection;
use Livewire\Component;
use App\Http\Traits\Requester\Tickets as RequestserTickets;

class Dashboard extends Component
{
    use RequestserTickets;

    protected $listeners = ['loadDashboard' => 'mount'];

    public Collection $openTickets;
    public Collection $onProcessTickets;
    public Collection $viewedTickets;
    public Collection $approvedTickets;
    public Collection $disapprovedTickets;
    public Collection $claimedTickets;
    public Collection $closedTickets;

    public function mount()
    {
        $this->openTickets = $this->getOpenTickets();
        $this->onProcessTickets = $this->getOnProcessTickets();
        $this->viewedTickets = $this->getViewedTickets();
        $this->approvedTickets = $this->getApprovedTickets();
        $this->disapprovedTickets = $this->getDisapprovedTickets();
        $this->claimedTickets = $this->getClaimedTickets();
        $this->closedTickets = $this->getClosedTickets();
    }

    public function render()
    {
        return view('livewire.requester.dashboard');
    }
}