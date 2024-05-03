<div>
    <li class="nav-item my-auto list-unstyled dropdown">
        <a class="nav-link mx-1" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            @if (auth()->user()->profile->picture)
                <img src="{{ Storage::url(auth()->user()->profile->picture) }}" class="nav__user__picture" alt="">
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
</div>
