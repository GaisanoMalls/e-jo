<div class="sidebar" id="sidebar__toggle" data-turbolinks="true">
    <div class="sidebar__user text-center">
        <div class="position-relative d-flex justify-content-center">
            @if (auth()->user()->profile->picture)
            <img src="{{ Storage::url(auth()->user()->profile->picture) }}" class="rounded-circle sidebar__userimage"
                alt="">
            @else
            <div class="d-flex align-items-center justify-content-center rounded-circle sidebar__userinitial">
                {{ auth()->user()->profile->getNameInitial() }}
            </div>
            @endif
            <div class="sidebar__badge__bottom">
                <span class="badge user__role__badge">
                    @switch(auth()->user()->role_id)
                    @case(App\MOdels\Role::SYSTEM_ADMIN)
                    System Admin
                    @break
                    @case(App\MOdels\Role::SERVICE_DEPARTMENT_ADMIN)
                    Service Department Admin
                    @break
                    @default
                    @case(App\MOdels\Role::APPROVER)
                    Approver
                    @break
                    @case(App\MOdels\Role::AGENT)
                    Agent
                    @break
                    @case(App\MOdels\Role::USER)
                    User
                    @break
                    @endswitch
                </span>
            </div>
        </div>
        <div class="my-3">
            <a href="">
                <h6 class="sidebar__userfullname">
                    {{ auth()->user()->profile->getFullName() }}
                </h6>
            </a>
            @if (auth()->user()->department)
            @switch(auth()->user()->role_id)
            @case(App\MOdels\Role::SERVICE_DEPARTMENT_ADMIN)
            <small class="fw-semibold px-3 py-2 rounded-5" style="font-size: 12px; background-color:
                #9DA85C; color: #FFFFFF; box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem
                0.25rem -0.0625rem rgba(20, 20, 20,
                0.07);" title="{{ auth()->user()->department->name ?? '' }}"">
                {{ Str::limit(auth()->user()->department->name, '30') ?? '' }}
            </small>
            @break
            @case(App\MOdels\Role::APPROVER)
            <small class=" fw-semibold px-3 py-2 rounded-5" style="font-size: 12px; background-color:
                #3B4053; color: #FFFFFF; box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem
                0.25rem -0.0625rem rgba(20, 20, 20,
                0.07);" title="{{ auth()->user()->department->name ?? '' }}"">
                {{ Str::limit(auth()->user()->department->name, '30') ?? '' }}
            </small>
            @break
            @case(App\MOdels\Role::AGENT)
            <small class=" fw-semibold px-3 py-2 rounded-5" style="font-size: 12px; background-color:
                #196837; color: #FFFFFF; box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem
                0.25rem -0.0625rem rgba(20, 20, 20,
                0.07);" title="{{ auth()->user()->department->name ?? '' }}">
                {{ Str::limit(auth()->user()->department->name, '30') ?? '' }}
            </small>
            @break
            @case(App\MOdels\Role::USER)
            <small class="fw-semibold px-3 py-2 rounded-5" style="font-size: 12px; background-color:
                #24695C; color: #FFFFFF; box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem
                0.25rem -0.0625rem rgba(20, 20, 20,
                0.07);" title="{{ auth()->user()->department->name ?? '' }}"">
                {{ Str::limit(auth()->user()->department->name, '30') ?? '' }}
            </small>
            @break
            @default
            <small class=" fw-semibold px-3 py-2 rounded-5" style="font-size: 12px; background-color:
                #F2F2F2; color: #5b5943; box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem
                0.25rem -0.0625rem rgba(20, 20, 20,
                0.07);">
                {{ Str::limit(auth()->user()->department->name, '30') ?? '' }}
            </small>
            @endswitch
            @endif
        </div>
        <p class=" sidebar__userdepartment">
            {{ auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN
            ? Str::limit(auth()->user()->getServiceDepartments() , 30) . " -"
            : (auth()->user()->role_id !== App\Models\Role::SYSTEM_ADMIN
            ? auth()->user()->serviceDepartment->name . " -" : '')
            ?? '' }}
            {{ auth()->user()->branch->name ?? '' }}
        </p>
        <div class="mt-3 d-flex staff__ticket__count justify-content-center">
            <li>
                <span class="counter">9</span>
                <p class="counter__label">Claimed</p>
            </li>
            <li>
                <span class="counter">95</span>
                <p class="counter__label">Answered </p>
            </li>
            <li>
                <span class="counter">2</span>
                <p class="counter__label">Resolved</p>
            </li>
        </div>
    </div>
    <nav style="margin-bottom: 80px;">
        <div class="main__navbar mx-4">
            <ul class="list-unstyled ps-0">
                <li class="mb-1">
                    <a href="{{ route('staff.dashboard') }}" class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                        {{ Route::is('staff.dashboard') ? 'sidebar__btn__active active' : '' }}">
                        <div
                            class=" d-flex align-items-center justify-content-center sidebar__button__icon__container fade__in__sidebar__icon__container">
                            <i class="bi bi-grid-1x2-fill"></i>
                        </div>
                        Dashboard
                    </a>
                </li>

                {{-- ! Disable this section for now until processflow from FPM is applied. --}}
                {{-- @if (auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN)
                <li class="mb-1">
                    <a href="{{ route('staff.service_dept_head.level_1_approval.index') }}"
                        class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                            {{ Route::is('staff.service_dept_head.level_1_approval.*') ? 'sidebar__btn__active active' : '' }}">
                        <div
                            class=" d-flex align-items-center justify-content-center sidebar__button__icon__container fade__in__sidebar__icon__container">
                            <i class="fa-solid fa-thumbs-up"></i>
                        </div>
                        For Approval
                    </a>
                </li>
                @endif --}}

                <li class="mb-1">
                    <button
                        class="btn btn-toggle d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                            sidebar__btn__collapse
                        {{ Route::is('staff.tickets.*') || Route::is('staff.ticket.*') ? 'sidebar__btn__active active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="true">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="bi bi-ticket-perforated-fill"></i>
                        </div>
                        Tickets
                    </button>
                    <div class="collapse {{ Route::is('staff.tickets.*') || Route::is('staff.ticket.*') ? 'show' : '' }}"
                        id="dashboard-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small sidebar__collapse__ul">
                            <li>
                                <a href="{{ route('staff.tickets.open_tickets') }}"
                                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                                                                {{ Route::is('staff.tickets.open_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                                        <span class="sidebar__btn__link__name ">Open</span>
                                    </div>
                                    {{-- <span class="badge sidebar__btn__link__badge">{{ $openTickets->count()
                                        }}</span> --}}
                                </a>
                            </li>
                            @if (auth()->user()->role_id !== App\Models\Role::AGENT)
                            <li>
                                <a href="{{ route('staff.tickets.viewed_tickets') }}"
                                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                                                                {{ Route::is('staff.tickets.viewed_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                                        <span class="sidebar__btn__link__name ">Viewed</span>
                                    </div>
                                    {{-- <span class="badge sidebar__btn__link__badge">30</span> --}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('staff.tickets.approved_tickets') }}"
                                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                                    {{ Route::is('staff.tickets.approved_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                                        <span class="sidebar__btn__link__name ">Approved</span>
                                    </div>
                                    {{-- <span class="badge sidebar__btn__link__badge">{{ $approvedTickets->count()
                                        }}</span> --}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('staff.tickets.disapproved_tickets') }}"
                                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                                    {{ Route::is('staff.tickets.disapproved_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                                        <span class="sidebar__btn__link__name ">Disapproved</span>
                                    </div>
                                    {{-- <span class="badge sidebar__btn__link__badge">{{ $approvedTickets->count()
                                        }}</span> --}}
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('staff.tickets.claimed_tickets') }}"
                                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                                    {{ Route::is('staff.tickets.claimed_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                                        <span class="sidebar__btn__link__name ">Claimed</span>
                                    </div>
                                    {{-- <span class="badge sidebar__btn__link__badge">{{ $claimedTickets->count()
                                        }}</span> --}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('staff.tickets.on_process_tickets') }}"
                                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                                    {{ Route::is('staff.tickets.on_process_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                                        <span class="sidebar__btn__link__name ">On Process</span>
                                    </div>
                                    {{-- <span class="badge sidebar__btn__link__badge">{{ $onProcessTickets->count()
                                        }}</span> --}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('staff.tickets.overdue_tickets') }}"
                                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                                    {{ Route::is('staff.tickets.overdue_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                                        <span class="sidebar__btn__link__name ">Overdue</span>
                                    </div>
                                    {{-- <span class="badge sidebar__btn__link__badge">30</span> --}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('staff.tickets.closed_tickets') }}"
                                    class="position-relative link-dark d-flex align-items-center text-decoration-none rounded justify-content-between sidebar__collapse__btnlink
                                    {{ Route::is('staff.tickets.closed_tickets') ? 'sidebar__collapse__btnlink__active' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="sidebar__active__dot position-absolute rounded-circle"></div>
                                        <span class="sidebar__btn__link__name ">Closed</span>
                                    </div>
                                    {{-- <span class="badge sidebar__btn__link__badge">30</span> --}}
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
                </li>
                {{-- <li class="mb-1">
                    <a href=""
                        class="btn d-flex btn-block align-items-center justify-content-between w-100 border-0 sidebar__buttons">
                        <div class="d-flex align-items-center gap-3 justify-content-between">
                            <div
                                class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            Assigned to me
                        </div>
                        <span class="badge sidebar__button__badge__count">9</span>
                    </a>
                </li>
                <li class="mb-1">
                    <a href=""
                        class="btn d-flex gap-3 btn-block align-items-center justify-content-between w-100 border-0 sidebar__buttons">
                        <div class="d-flex align-items-center gap-3 justify-content-between">
                            <div
                                class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                                <i class="bi bi-bookmarks-fill"></i>
                            </div>
                            Bookmarks
                        </div>
                        <span class="badge sidebar__button__badge__count">3</span>
                    </a>
                </li> --}}
                <li class="mb-1">
                    <a href="" class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        Statistics
                    </a>
                </li>
                @if (auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN)
                <li class="mb-1">
                    <a href="{{ route('staff.announcement.home') }}" class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                        {{ Route::is('staff.announcement.*') ? 'sidebar__btn__active active' : '' }}">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                        Announcements
                    </a>
                </li>
                @endif
                <hr>
                @if (auth()->user()->role_id === App\Models\Role::SYSTEM_ADMIN)
                <li class="mb-1">
                    <a href="{{ route('staff.manage.home') }}" class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                        {{ Route::is('staff.manage.*') ? 'sidebar__btn__active active' : '' }}">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        Manage
                    </a>
                </li>
                @endif
                <li class="mb-1">
                    <a href="" class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        </div>
                        Reports
                    </a>
                </li>
                <hr>
                <li class="mb-1">
                    <a href="{{ route('staff.directory.index') }}" class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons
                        {{ Route::is('staff.directory.*') ? 'sidebar__btn__active active' : '' }}">
                        <div class="d-flex align-items-center justify-content-center sidebar__button__icon__container">
                            <i class="fa-solid fa-address-book"></i>
                        </div>
                        Directories
                    </a>
                </li>
                <li class="mb-1">
                    <a href="" class="btn d-flex gap-3 btn-block align-items-center w-100 border-0 sidebar__buttons">
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