<div wire:poll.7s>
    <a class="nav-link icon__nav__link position-relative" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#notificationCanvas">
        <i class="bi bi-bell-fill"></i>
        @if (!auth()->user()->unreadNotifications->isEmpty())
        <i class='bx bxs-circle position-absolute' style="top: 8px; right: 6px; color: #D32839; font-size: 11px;"></i>
        @endif
    </a>
</div>