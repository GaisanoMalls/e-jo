<div class="row mx-0">
    <ul class="nav nav-pills nav-fill flex-nowrap ticket__tab__header">
        <li class="nav-item">
            <a href="{{ route('user.tickets.open_tickets') }}" class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('user.tickets.open_tickets') ? 'active' : '' }}">
                <span class="ticket__count__tab">{{ $openTickets->count() }}</span>
                Open
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.tickets.on_process_tickets') }}" class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('user.tickets.on_process_tickets') ? 'active' : '' }}">
                <span class="ticket__count__tab">{{ $onProcessTickets->count() }}</span>
                On Process
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link" href="#">
                <span class="ticket__count__tab">13</span>
                Viewed
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link" href="#">
                <span class="ticket__count__tab">2</span>
                On Hold
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link" href="#">
                <span class="ticket__count__tab">13</span>
                Approved
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link" href="#">
                <span class="ticket__count__tab">8</span>
                Disapproved
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link" href="#">
                <span class="ticket__count__tab">4</span>
                Reopened
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.tickets.closed_tickets') }}" class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('user.tickets.closed_tickets') ? 'active' : '' }}">
                <span class="ticket__count__tab">{{ $closedTickets->count() }}</span>
                Closed
            </a>
        </li>
    </ul>
</div>
