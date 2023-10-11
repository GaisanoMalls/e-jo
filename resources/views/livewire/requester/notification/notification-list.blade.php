<div wire:poll.7s>
    @foreach ($userNotifications as $notification)
    @if (!$notification->read())
    <div wire:key="notification-{{ $notification->id }}" wire:click="readNotification('{{ $notification->id }}')"
        class="d-flex justify-content-between px-4 py-3 border-top notification__card {{ $notification->read() ? 'text-muted' : ''}}">
        @else
        <div class="d-flex justify-content-between px-4 py-3 border-top notification__card">
            @endif
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
            <div class="d-flex flex-column justify-content-between text-end">
                <small class="notification__running__time">
                    {{ $notification->created_at->diffForHumans(null, true) }} ago
                </small>
                @if ($notification->read())
                <button
                    class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle ms-auto btn__delete__notification"
                    wire:click="deleteNotification('{{ $notification->id }}')">
                    <i class="bi bi-trash"></i>
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>