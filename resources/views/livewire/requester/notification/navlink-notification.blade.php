<div>
    <li class="nav-item my-auto list-unstyled dropdown">
        <a wire:click="requesterShowNotifications" class="nav-link icon__nav__link position-relative" type="button"
            aria-expanded="false" data-bs-toggle="offcanvas" data-bs-target="#notificationCanvas">
            <i class="fa-solid fa-bell"></i>
            @if (auth()->user()->unreadNotifications->isNotEmpty())
                <div class="d-flex align-items-center justify-content-center position-absolute rounded-circle"
                    style="background-color: #D32839; border: 0.09rem solid white; top: 10px; right: 4px; height: 9px; width: 9px;">
                </div>
            @endif
        </a>
    </li>
</div>
