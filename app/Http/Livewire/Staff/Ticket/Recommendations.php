<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\IctRecommendation;
use Livewire\Component;

class Recommendations extends Component
{
    public function render()
    {
        $recommendations = IctRecommendation::with(['ticket', 'approver', 'requestedByServiceDeptAdmin.profile'])->get();
        return view('livewire.staff.ticket.recommendations', compact('recommendations'));
    }
}
