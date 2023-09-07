<div class="offcanvas offcanvas-end rounded-3 notification__canvas" tabindex="-1" id="notificationCanvas">
    @if (!auth()->user()->notifications->isEmpty())
    <div class="offcanvas-header align-items-baseline p-4">
        <div class="d-flex flex-column gap-1">
            <div class="d-flex align-items-center gap-2">
                <h6 class="offcanvas-title fw-bold" id="offcanvasRightLabel">Notifications</h6>
                <small class="rounded-2 d-flex justify-content-center align-items-center shadow-sm notification__count">
                    {{ auth()->user()->notifications->count() }}
                </small>
            </div>
            <div class="d-flex align-items-center gap-1 text-small">
                <form action="{{ route('approver.notification.mark_all_as_read') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-sm p-0">Mark all as read</button>
                </form>
                •
                <form action="{{ route('approver.notification.clear') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm p-0">Clear</button>
                </form>
            </div>
        </div>
        <button type="button" class="btn btn-sm p-0 d-flex align-items-center justify-content-center"
            data-bs-dismiss="offcanvas">
            <i class="bi bi-x fs-4"></i>
        </button>
    </div>
    <div class="offcanvas-body py-0 px-0">
        @foreach (auth()->user()->notifications()->latest()->get() as $notification)
        <div class="d-flex justify-content-between px-4 py-3 border-top notification__card {{ $notification->read() ? 'text-muted' : ''}}"
            data-notification-id="{{ !$notification->read() ? $notification->id : '' }}">
            <div class="d-flex flex-column gap-1">
                <h6 class="mb-1 notification__message {{ $notification->read() ? 'fw-normal' : ''}}">{{
                    $notification->data['title'] }}</h6>
                <div class="d-flex align-items-center gap-2">
                    @if ($notification->data['sender']['profilePicture'] == '/storage/' ||
                    $notification->data['sender']['profilePicture'] == null)

                    <div class="d-flex align-items-center justify-content-center sender__name__initial gap-2">
                        {{ $notification->data['sender']['nameInitial'] }}
                    </div>
                    @else
                    <img src="{{ $notification->data['sender']['profilePicture'] }}"
                        class="notification__sender__picture" alt="">
                    @endif
                    <small class="notification__sender__fullname">
                        {{ $notification->data['sender']['fullName'] }}
                        {{ $notification->data['message'] }}
                    </small>
                </div>
                <small class="notification__date__time">
                    {{ Carbon\Carbon::parse($notification->created_at)->format('M d, Y') }}
                    -
                    {{ $notification->created_at->format('g:i A') }}
                </small>
            </div>
            <small class="notification__running__time">
                {{ $notification->created_at->diffForHumans(null, true) }} ago
            </small>
        </div>
        @endforeach
    </div>
    @else
    <div class="offcanvas-header align-items-baseline justify-content-end p-4">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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