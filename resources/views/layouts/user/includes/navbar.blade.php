<nav class="navbar navbar-expand-lg bg-white px-5 py-3 sticky-top">
    <div class="container gap-5">
        <a href="{{ route('user.dashboard') }}"
            class="navbar-brand d-flex gap-2 align-items-center justify-content-center">
            <img src="{{ asset('images/gmall.png') }}" class="company__logo" alt="GMall Ticketing System">
            <h5 class="mb-0 company__app__name position-relative">
                E-JO
                <span class="position-absolute lbl__system">Ticketing System</span>
            </h5>
        </a>
        <button class="navbar-toggler border-0 p-0 custom__navbar__toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fa-solid fa-bars mx-auto"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-4">
                <li class="nav-item custom__nav__item text-center">
                    <a class="nav-link custom__nav__link" href="#">Knowledge Base</a>
                </li>
                <li class="nav-item custom__nav__item text-center">
                    <a class="nav-link custom__nav__link" target="_blank"
                        href="{{ route('feedback.index') }}">Feedback</a>
                </li>
                <li class="nav-item custom__nav__item text-center dropdown">
                    <a class="nav-link custom__nav__link dropdown-toggle" href="" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Approver
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end slideIn animate custom__dropdown__menu">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3" href="#">
                                <i class="fa-solid fa-check"></i>
                                Approved
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3" href="#">
                                <i class="fa-solid fa-xmark"></i>
                                Disapproved</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item custom__nav__item text-center">
                    <a class="nav-link custom__nav__link
                    {{ Route::is('user.tickets.*') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('user.tickets.open_tickets') }}">Tickets</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-4">
                <li class="nav-item my-auto custom__nav__item">
                    <button href="" type="button" class="btn btn__nav__create__ticket" data-bs-toggle="modal"
                        data-bs-target="#createTicketModal" id="navBtnCreateNewTicket">Create Ticket</button>
                </li>
                <li class="nav-item my-auto dropdown">
                    <a class="nav-link icon__nav__link" href="" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-bell-fill"></i>
                    </a>
                    <ul
                        class="dropdown-menu dropdown-menu-end slideIn animate custom__dropdown__menu notification__dropdown__menu">
                        <div class="d-flex align-items-center justify-content-between notification__header">
                            <h6 class="mb-0 title">Notifications</h6>
                            <form action="" method="">
                                <button type="submit"
                                    class="btn btn-sm p-0 d-flex align-items-center gap-2 btn__mark__all__as__read">
                                    <i class="fa-solid fa-check-double"></i>
                                    Mark all as read
                                </button>
                            </form>
                        </div>
                        <li>
                            <a class="btn dropdown-item dropdown__menu__items" href="">
                                Notifications
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item my-auto dropdown">
                    <a class="nav-link icon__nav__link" href="" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-solid fa-bullhorn"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end slideIn animate custom__dropdown__menu">
                        <li>
                            <a class="btn dropdown-item dropdown__menu__items" href="">
                                Announcements
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item my-auto dropdown">
                    <a class="nav-link mx-1" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if (auth()->user()->profile->picture)
                        <img src="{{ asset('storage/' . auth()->user()->profile->picture) }}" class="nav__user__picture"
                            alt="">
                        @else
                        <div class="nav__user__picture d-flex align-items-center p-2 justify-content-center text-white"
                            style="background-color: #1A2E35; border: 3px solid #385A64; font-size: 13px;">
                            {{ auth()->user()->profile->getNameInitial() }}</div>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end slideIn animate custom__dropdown__menu">
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
            </ul>
        </div>
    </div>
</nav>
