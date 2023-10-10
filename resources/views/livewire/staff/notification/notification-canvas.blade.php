<div wire:poll.7s>
    <div wire:ignore.self class="offcanvas offcanvas-end rounded-3 shadow border-0 notification__canvas"
        data-bs-backdrop="false" tabindex="-1" id="notificationCanvas">
        @if (!auth()->user()->notifications->isEmpty())
        <div class="offcanvas-header align-items-baseline p-4">
            <div class="d-flex flex-column gap-1">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="offcanvas-title fw-bold" id="offcanvasRightLabel">Notifications</h6>
                    @livewire('staff.notification.unread-notification-count')
                </div>
                <div class="d-flex align-items-center gap-1 text-small">
                    <button type="submit" class="btn btn-sm p-0 d-flex align-items-center gap-2"
                        wire:click="markAllAsRead">
                        <div wire:target="markAllAsRead" wire:loading
                            class="spinner-border spinner-border-sm loading__spinner" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Mark all as read
                    </button>
                    •
                    <button type="submit" class="btn btn-sm p-0 d-flex align-items-center gap-2"
                        wire:click="clearNotifications">
                        <div wire:target="clearNotifications" wire:loading
                            class="spinner-border spinner-border-sm loading__spinner" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span wire:target="clearNotifications" wire:loading.remove>Clear</span>
                        <span wire:target="clearNotifications" wire:loading>Clearing...</span>
                    </button>
                </div>
            </div>
            <button type="button"
                class="btn d-flex align-items-center justify-content-center btn__close__notification__canvas"
                data-bs-dismiss="offcanvas">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="offcanvas-body py-0 px-0">
            @livewire('staff.notification.notification-list')
        </div>
        @else
        <div class="offcanvas-header align-items-baseline justify-content-end p-4">
            <button type="button"
                class="btn d-flex align-items-center justify-content-center btn__close__notification__canvas"
                data-bs-dismiss="offcanvas">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="d-flex flex-column align-items-center justify-content-center px-2 no__notification">
            <div
                class="d-flex align-items-center justify-content-center rounded-circle bg-light fs-5 mb-3 notification__bell__container">
                <i class="bi bi-bell-slash"></i>
            </div>
            <h6 class="mb-0 fw-bold message__1">No notifications</h6>
            <small class="text-muted message__2">Please check again later.</small>
        </div>
        @endif
    </div>
</div>