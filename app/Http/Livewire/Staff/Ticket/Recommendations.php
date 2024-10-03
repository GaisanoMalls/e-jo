<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Recommendation;
use Livewire\Component;

class Recommendations extends Component
{
    public function render()
    {
        $recommendations = Recommendation::with(['ticket', 'requestedByServiceDeptAdmin.profile'])->get();
        return view('livewire.staff.ticket.recommendations', compact('recommendations'));
    }
}
