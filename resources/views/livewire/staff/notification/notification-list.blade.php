<div wire:poll.7s>
    @foreach ($userNotifications->sortByDesc('created_at') as $notification)
        <div
            class="d-flex justify-content-between px-4 py-3 border-top notification__card {{ $notification->read() ? 'text-muted' : 'unread__border' }}">
            <div class="d-flex flex-column gap-1">
                <h6 class="mb-0 notification__message {{ $notification->read() ? 'fw-normal' : '' }}">
                    {{ $notification->data['title'] }}</h6>
                <small class="notification__date__time">
                    {{ Carbon\Carbon::parse($notification->created_at)->format('M d, Y') }}
                    -
                    {{ $notification->created_at->format('g:i A') }}
                </small>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <small class="notification__sender__fullname">
                        {{ $notification->data['message'] }}
                    </small>
                </div>
                <span wire:click="readNotification('{{ $notification->id }}')" class="btn__view__notification">
                    View
                </span>
            </div>
            <div class="d-flex flex-column justify-content-between text-end">
                <small class="notification__running__time">
                    {{ $notification->created_at->diffForHumans(null, true) }} ago
                </small>
                @if ($notification->read())
                    <button
                        class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle ms-auto btn__delete__notification"
                        wire:click.prevent="deleteNotification('{{ $notification->id }}')">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
            </div>
        </div>
    @endforeach
</div>
