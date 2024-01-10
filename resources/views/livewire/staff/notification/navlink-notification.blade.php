<div wire:poll.visible.7s>
    <a wire:click="staffShowNotifications" class="nav-link position-relative" type="button" aria-expanded="false"
        data-bs-toggle="offcanvas" data-bs-target="#notificationCanvas">
        <i class="fa-solid fa-bell"></i>
        @if (auth()->user()->unreadNotifications->isNotEmpty())
            <div class="d-flex align-items-center justify-content-center position-absolute rounded-circle"
                style="background-color: #D32839; border: 0.09rem solid white; top: 5px; right: 5px; height: 9px; width: 9px;">
            </div>
        @endif
    </a>
</div>
