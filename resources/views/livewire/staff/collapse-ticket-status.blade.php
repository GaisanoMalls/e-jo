<div>
    <button wire:ignore.self
        class="btn btn-toggle d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons sidebar__btn__collapse {{ Route::is('staff.tickets.*') || Route::is('staff.ticket.*') ? 'sidebar__btn__active active' : '' }}"
        data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="true"
        wire:click="$emit('loadSidebarCollapseTicketStatus')">
        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
            <i class="bi bi-ticket-perforated-fill"></i>
        </div>
        Tickets
    </button>
    <div wire:ignore.self
        class="collapse {{ Route::is('staff.tickets.*') || Route::is('staff.ticket.*') ? 'show' : '' }}"
        id="dashboard-collapse">
        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small sidebar__collapse__ul">
            <li>
                <a href="{{ route('staff.tickets.open_tickets') }}"
                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                    {{ Route::is('staff.tickets.open_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}"
                    wire:ignore.self>
                    <div class="d-flex align-items-center">
                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                        <span class="sidebar__btn__link__name ">Open</span>
                    </div>
                    <span class="badge sidebar__btn__link__badge">
                        {{ $openTickets->count() }}
                    </span>
                </a>
            </li>
            @if (!auth()->user()->hasRole(App\Models\Role::AGENT))
                <li>
                    <a href="{{ route('staff.tickets.viewed_tickets') }}"
                        class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                    {{ Route::is('staff.tickets.viewed_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}"
                        wire:ignore.self>
                        <div class="d-flex align-items-center">
                            <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                            <span class="sidebar__btn__link__name ">Viewed</span>
                        </div>
                        <span class="badge sidebar__btn__link__badge">
                            {{ $viewedTickets->count() }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.tickets.approved_tickets') }}"
                        class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                    {{ Route::is('staff.tickets.approved_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}"
                        wire:ignore.self>
                        <div class="d-flex align-items-center">
                            <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                            <span class="sidebar__btn__link__name ">Approved</span>
                        </div>
                        <span class="badge sidebar__btn__link__badge">
                            {{ $approvedTickets->count() }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.tickets.disapproved_tickets') }}"
                        class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                    {{ Route::is('staff.tickets.disapproved_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}"
                        wire:ignore.self>
                        <div class="d-flex align-items-center">
                            <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                            <span class="sidebar__btn__link__name ">Disapproved</span>
                        </div>
                        <span class="badge sidebar__btn__link__badge">
                            {{ $disapprovedTickets->count() }}
                        </span>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('staff.tickets.claimed_tickets') }}"
                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                    {{ Route::is('staff.tickets.claimed_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}"
                    wire:ignore.self>
                    <div class="d-flex align-items-center">
                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                        <span class="sidebar__btn__link__name ">Claimed</span>
                    </div>
                    <span class="badge sidebar__btn__link__badge">
                        {{ $claimedTickets->count() }}
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.tickets.on_process_tickets') }}"
                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                    {{ Route::is('staff.tickets.on_process_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}"
                    wire:ignore.self>
                    <div class="d-flex align-items-center">
                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                        <span class="sidebar__btn__link__name ">On Process</span>
                    </div>
                    <span class="badge sidebar__btn__link__badge">
                        {{ $onProcessTickets?->count() }}
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.tickets.overdue_tickets') }}"
                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                    {{ Route::is('staff.tickets.overdue_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}"
                    wire:ignore.self>
                    <div class="d-flex align-items-center">
                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                        <span class="sidebar__btn__link__name ">Overdue</span>
                    </div>
                    <span class="badge sidebar__btn__link__badge">
                        {{ $overdueTickets->count() }}
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.tickets.closed_tickets') }}"
                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                    {{ Route::is('staff.tickets.closed_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}"
                    wire:ignore.self>
                    <div class="d-flex align-items-center">
                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                        <span class="sidebar__btn__link__name ">Closed</span>
                    </div>
                    <span class="badge sidebar__btn__link__badge">
                        {{ $closedTickets->count() }}
                    </span>
                </a>
            </li>
            {{-- <li>
                <a href="" class="position-relative link-dark d-flex align-items-center text-decoration-none
    rounded justify-content-between sidebar__collapse__btnlink">
                    <div class="d-flex align-items-center">
                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                        <span class="sidebar__btn__link__name ">Reviews</span>
                    </div>
                    <span class="badge sidebar__btn__link__badge">30</span>
                </a>
            </li> --}}
        </ul>
    </div>
</div>
