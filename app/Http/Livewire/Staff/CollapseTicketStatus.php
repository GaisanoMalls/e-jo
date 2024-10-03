<?php

namespace App\Http\Livewire\Staff;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Livewire\Component;

class CollapseTicketStatus extends Component
{
    use TicketsByStaffWithSameTemplates;

    protected $listeners = ['loadSidebarCollapseTicketStatus' => '$refresh'];

    public function tiggerEvents()
    {
        $events = [
            'loadSidebarCollapseTicketStatus',
            'staffLoadNavlinkNotification',
            'staffLoadNotificationList',
            'staffLoadNotificationCanvas',
            'staffLoadUnreadNotificationCount',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    public function render()
    {
        return view('livewire.staff.collapse-ticket-status');
    }
}