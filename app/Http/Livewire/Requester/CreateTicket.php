<?php

namespace App\Http\Livewire\Requester;

use App\Http\Requests\Requester\StoreTicketRequest;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateTicket extends Component
{
    use WithFileUploads;

    public int $branch_id, $service_department_id, $team_id, $help_topic_id, $priority_level_id, $sla_id;
    public string $subject, $description;

    public function rules()
    {
        return (new StoreTicketRequest())->rules();
    }

    public function messages()
    {
        return (new StoreTicketRequest())->messages();
    }

    public function render()
    {
        return view('livewire.requester.create-ticket');
    }
}