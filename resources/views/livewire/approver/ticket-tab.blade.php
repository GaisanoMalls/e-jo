<div class="row mx-0">
    <ul class="navbar-nav d-flex flex-row flex-nowrap gap-3 py-2 ticket__tab__header">
        <li class="nav-item">
            <a href="{{ route('approver.tickets.open') }}"
                class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('approver.tickets.open') ? 'ticket__tab__link active' : '' }}"
                wire:ignore.self>
                <span class="ticket__count__tab">{{ $countOpenTickets }}</span>
                Open
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('approver.tickets.viewed') }}"
                class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('approver.tickets.viewed') ? 'ticket__tab__link active' : '' }}"
                wire:ignore.self>
                <span class="ticket__count__tab">{{ $countViewedTickets }}</span>
                Viewed
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('approver.tickets.approved') }}"
                class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('approver.tickets.approved') ? 'ticket__tab__link active' : '' }}"
                wire:ignore.self>
                <span class="ticket__count__tab">{{ $countApprovedTickets }}</span>
                Approved
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('approver.tickets.disapproved') }}"
                class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('approver.tickets.disapproved') ? 'ticket__tab__link active' : '' }}"
                wire:ignore.self>
                <span class="ticket__count__tab">{{ $countDisapprovedTickets }}</span>
                Disapproved
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('approver.tickets.on_process') }}"
                class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('approver.tickets.on_process') ? 'ticket__tab__link active' : '' }}"
                wire:ignore.self>
                <span class="ticket__count__tab">{{ $countOnProcessTickets }}</span>
                On Process
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('approver.tickets.claimed') }}"
                class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('approver.tickets.claimed') ? 'ticket__tab__link active' : '' }}"
                wire:ignore.self>
                <span class="ticket__count__tab">{{ $countClaimedTickets }}</span>
                Claimed
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('approver.tickets.closed') }}"
                class="nav-link d-flex align-items-center justify-content-center gap-3 ticket__tab__link
                {{ Route::is('approver.tickets.closed') ? 'ticket__tab__link active' : '' }}"
                wire:ignore.self>
                <span class="ticket__count__tab">{{ $countClosedTickets }}</span>
                Closed
            </a>
        </li>
    </ul>
</div>
