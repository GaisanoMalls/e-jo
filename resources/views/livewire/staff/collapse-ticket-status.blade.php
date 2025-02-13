<div>
    <button wire:ignore.self
        class="btn btn-toggle d-flex gap-3 justify-content-between btn-block align-items-center w-100 border-0 sidebar__buttons sidebar__btn__collapse {{ Route::is('staff.tickets.*') || Route::is('staff.ticket.*') ? 'sidebar__btn__active active' : '' }}"
        data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="true" wire:click="tiggerEvents">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                <i class="bi bi-ticket-perforated-fill"></i>
            </div>
            Tickets
        </div>
    </button>
    <div wire:ignore.self class="collapse {{ Route::is('staff.tickets.*') || Route::is('staff.ticket.*') ? 'show' : '' }}" id="dashboard-collapse">
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
                    @if ($this->getOpenTickets()->count() >= 1)
                        <span class="badge sidebar__btn__link__badge">
                            {{ $this->getOpenTickets()->count() }}
                        </span>
                    @endif
                </a>
            </li>
            @if (!auth()->user()->isAgent())
                <li>
                    <a href="{{ route('staff.tickets.viewed_tickets') }}"
                        class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                    {{ Route::is('staff.tickets.viewed_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}"
                        wire:ignore.self>
                        <div class="d-flex align-items-center">
                            <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                            <span class="sidebar__btn__link__name ">Viewed</span>
                        </div>
                        @if ($this->getViewedTickets()->count() >= 1)
                            <span class="badge sidebar__btn__link__badge">
                                {{ $this->getViewedTickets()->count() }}
                            </span>
                        @endif
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
                        @if ($this->getApprovedTickets()->count() >= 1)
                            <span class="badge sidebar__btn__link__badge">
                                {{ $this->getApprovedTickets()->count() }}
                            </span>
                        @endif
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
                        @if ($this->getDisapprovedTickets()->count() >= 1)
                            <span class="badge sidebar__btn__link__badge">
                                {{ $this->getDisapprovedTickets()->count() }}
                            </span>
                        @endif
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
                    @if ($this->getClaimedTickets()->count() >= 1)
                        <span class="badge sidebar__btn__link__badge">
                            {{ $this->getClaimedTickets()->count() }}
                        </span>
                    @endif
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
                    @if ($this->getOnProcessTickets()->count() >= 1)
                        <span class="badge sidebar__btn__link__badge">
                            {{ $this->getOnProcessTickets()->count() }}
                        </span>
                    @endif
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
                    @if ($this->getOverdueTickets()->count() >= 1)
                        <span class="badge sidebar__btn__link__badge">
                            {{ $this->getOverdueTickets()->count() }}
                        </span>
                    @endif
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
                    @if ($this->getClosedTickets()->count() >= 1)
                        <span class="badge sidebar__btn__link__badge">
                            {{ $this->getClosedTickets()->count() }}
                        </span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</div>
