<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\BasicModelQueries;

class TicketStatusController extends Controller
{
    use BasicModelQueries;
    public function __invoke()
    {
        $statuses = $this->queryTicketStatus();
        return view('layouts.staff.system_admin.manage.ticket_statuses.status_index', compact('statuses'));
    }
}