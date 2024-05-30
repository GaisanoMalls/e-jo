@php
    use App\Models\Role;
@endphp

<div class="sidebar" id="sidebar__toggle" x-data="{ scrollPosition: localStorage.getItem('sidebarScrollPosition') || 0 }" x-init="() => { $el.scrollTop = scrollPosition }"
    @scroll="scrollPosition = $el.scrollTop; localStorage.setItem('sidebarScrollPosition', scrollPosition)">
    @livewire('staff.sidebar-account')
    <nav style="margin-bottom: 80px;">
        <div class="main__navbar mx-4">
            <ul class="list-unstyled ps-0">
                <li class="mb-1">
                    <a href="{{ route('staff.dashboard') }}"
                        class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                        {{ Route::is('staff.dashboard') ? 'sidebar__btn__active active' : '' }}">
                        <div
                            class=" d-flex align-items-center justify-content-center sidebar__button__icon__container fade__in__sidebar__icon__container">
                            <i class="bi bi-grid-1x2-fill"></i>
                        </div>
                        Dashboard
                    </a>
                </li>
                @if (auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN))
                    <li class="mb-1">
                        <a href="{{ route('staff.manual_ticket_assign.to_assign') }}"
                            class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons -bottom-3
                            {{ Route::is('staff.manual_ticket_assign.*') ? 'sidebar__btn__active active' : '' }}">
                            <div
                                class=" d-flex align-items-center justify-content-center sidebar__button__icon__container fade__in__sidebar__icon__container">
                                <i class="bi bi-person-fill-check"></i>
                            </div>
                            Ticket Assigning
                        </a>
                    </li>
                @endif
                <li class="mb-1">
                    @livewire('staff.collapse-ticket-status')
                </li>
                @if (auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN))
                    <li class="mb-1">
                        <a href="{{ route('staff.feedbacks') }}"
                            class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                            {{ Route::is('staff.feedbacks') ? 'sidebar__btn__active active' : '' }}">
                            <div
                                class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            Feedbacks
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('staff.announcement.home') }}"
                            class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                            {{ Route::is('staff.announcement.*') ? 'sidebar__btn__active active' : '' }}">
                            <div
                                class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                                <i class="fa-solid fa-bullhorn"></i>
                            </div>
                            Announcements
                        </a>
                    </li>
                @endif
                <li class="mb-1">
                    <a href="{{ route('staff.my_bookmarks.my_bookmarked_tickets') }}"
                        class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                        {{ Route::is('staff.my_bookmarks.*') ? 'sidebar__btn__active active' : '' }}">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="fa-solid fa-bookmark"></i>
                        </div>
                        Bookmarks
                    </a>
                </li>
                <hr>
                @if (auth()->user()->hasRole(Role::SYSTEM_ADMIN))
                    <li class="mb-1">
                        <a href="{{ route('staff.manage.roles_and_permissions.index') }}"
                            class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                            {{ Route::is('staff.manage.*') ? 'sidebar__btn__active active' : '' }}">
                            <div
                                class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            Manage
                        </a>
                    </li>
                @endif
                <li class="mb-1">
                    <a href=""
                        class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        Statistics
                    </a>
                </li>
                <li class="mb-1">
                    <a href=""
                        class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        </div>
                        Reports
                    </a>
                </li>
                <hr>
                <li class="mb-1">
                    <a href="{{ route('staff.directory.index') }}"
                        class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                        {{ Route::is('staff.directory.*') ? 'sidebar__btn__active active' : '' }}">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="fa-solid fa-address-book"></i>
                        </div>
                        Directories
                    </a>
                </li>
                <li class="mb-1">
                    <a href=""
                        class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                        Knowledge Base
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
