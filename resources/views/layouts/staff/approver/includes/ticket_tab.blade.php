<div class="row mx-0">
    <ul class="navbar-nav d-flex flex-row flex-nowrap gap-3 py-2 ticket__tab__header">
        <li class="nav-item">
            <a href="{{ route('approver.tickets.open') }}" class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('approver.tickets.open') ? 'ticket__tab__link active' : '' }}">
                <span class="ticket__count__tab">{{ $openTickets->count() }}</span>
                Open
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('approver.tickets.approved') }}" class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('approver.tickets.approved') ? 'ticket__tab__link active' : '' }}">
                <span class="ticket__count__tab">{{ $approvedTickets->count() }}</span>
                Approved
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('approver.tickets.disapproved') }}" class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('approver.tickets.disapproved') ? 'ticket__tab__link active' : '' }}">
                <span class="ticket__count__tab">{{ $disapprovedTickets->count() }}</span>
                Disapproved
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link">
                <span class="ticket__count__tab">13</span>
                On Hold
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link" href="#">
                <span class="ticket__count__tab">4</span>
                On Process
            </a>
        </li>
    </ul>
</div>
