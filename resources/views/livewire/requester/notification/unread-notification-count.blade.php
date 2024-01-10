<div>
    <small class="rounded-2 d-flex justify-content-center align-items-center shadow-sm notification__count">
        {{ auth()->user()->unreadNotifications->count() }}
    </small>
</div>
