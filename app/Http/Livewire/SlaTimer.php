<?php

namespace App\Http\Livewire;

use App\Http\Traits\Utils;
use App\Models\Ticket;
use Livewire\Component;

class SlaTimer extends Component
{
    use Utils;

    public Ticket $ticket;

    protected $listeners = ['loadSlaTimer' => '$refresh'];

    public function slaTimer()
    {
        return $this->ticketSLATimer($this->ticket);
    }

    public function render()
    {
        return view('livewire.sla-timer', [
            'slaTimer' => $this->slaTimer(),
        ]);
    }
}
