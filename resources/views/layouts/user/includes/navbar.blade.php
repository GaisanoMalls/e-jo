<nav class="navbar navbar-expand-lg bg-white py-3 px-lg-3 px-sm-2 sticky-top">
    <div class="container-fluid gap-2">
        <a href="{{ route('user.dashboard') }}"
            class="navbar-brand d-flex gap-2 align-items-center justify-content-center">
            <img src="{{ asset('images/gmall.png') }}" class="company__logo" alt="GMall Ticketing System">
            <h5 class="mb-0 company__app__name position-relative">
                E-JO
                <span class="position-absolute lbl__system">System</span>
            </h5>
        </a>
        <div class="d-flex align-items-center" style="gap: 1.9rem;">
            <li class="nav-item my-auto list-unstyled dropdown d-block d-lg-none">
                <a wire:click="requesterShowNotifications" class="nav-link icon__nav__link position-relative"
                    type="button" aria-expanded="false" data-bs-toggle="offcanvas"
                    data-bs-target="#notificationCanvas">
                    <i class="fa-solid fa-bell"></i>
                    @if (auth()->user()->unreadNotifications->isNotEmpty())
                        <div class="d-flex align-items-center justify-content-center position-absolute rounded-circle"
                            style="background-color: #D32839; border: 0.09rem solid white; top: 10px; right: 4px; height: 9px; width: 9px;">
                        </div>
                    @endif
                </a>
            </li>
            <button class="navbar-toggler border-0 p-0 custom__navbar__toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fa-solid fa-bars mx-auto"></i>
            </button>
            <li class="nav-item my-auto dropdown list-unstyled d-block d-lg-none">
                <a class="nav-link mx-1" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    @if (auth()->user()->profile->picture)
                        <img src="{{ Storage::url(auth()->user()->profile->picture) }}" class="nav__user__picture"
                            alt="">
                    @else
                        <div class="nav__user__picture d-flex align-items-center p-2 justify-content-center text-white"
                            style="background-color: #1A2E35; border: 3px solid #385A64; font-size: 13px;">
                            {{ auth()->user()->profile->getNameInitial() }}</div>
                    @endif
                </a>
                <ul
                    class="dropdown-menu dropdown-menu-end slideIn animate custom__dropdown__menu mobile__responsive__nav">
                    <li>
                        <a class="btn dropdown-item d-flex align-items-center gap-3 dropdown__menu__items"
                            href="{{ route('user.account_settings.profile') }}">
                            <i class="bi bi-gear-fill"></i>
                            Account Settings
                        </a>
                    </li>
                    <li>
                        <button class="btn dropdown-item d-flex align-items-center gap-3 dropdown__menu__items"
                            data-bs-toggle="modal" data-bs-target="#confirmLogout" type="button">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            Log Out
                        </button>
                    </li>
                </ul>
            </li>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-4 gap-sm-2">
                <li class="nav-item custom__nav__item text-lg-center text-sm-start">
                    <a class="nav-link d-flex align-item-center justify-content-between justify-content-md-between custom__nav__link"
                        target="_blank" href="{{ route('feedback.index') }}">
                        Feedback
                        <i class="bi bi-arrow-right-short d-lg-none d-sm-block"></i>
                    </a>
                </li>
                <li class="nav-item custom__nav__item text-lg-center text-sm-start">
                    <a class="nav-link d-flex align-item-center justify-content-between justify-content-md-between custom__nav__link
                    {{ Route::is('user.tickets.*') ? 'active' : '' }}"
                        aria-current="page" href="{{ route('user.tickets.open_tickets') }}">
                        Tickets
                        <i class="bi bi-arrow-right-short d-lg-none d-sm-block"></i>
                    </a>
                </li>
                <li class="nav-item custom__nav__item text-lg-center text-sm-start d-lg-none d-block">
                    <button type="button" class="btn w-100 btn__nav__create__ticket" data-bs-toggle="modal"
                        data-bs-target="#createTicketModal" wire:click="clearModalErrorMessages">Create Ticket</button>
                </li>
            </ul>
            <ul class="navbar-nav d-inline-flex align-items-center ms-auto mb-2 mb-lg-0 gap-4 d-none d-lg-flex">
                @livewire('requester.ticket.create-ticket-button')
                @livewire('requester.notification.navlink-notification')
                @livewire('requester.navbar-profile-picture')
            </ul>
        </div>
    </div>
</nav>
