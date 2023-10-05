<div>
    @if ($latestClarification)
    <div class="my-4 d-flex flex-column gap-3 reply__ticket__info">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                @if ($latestClarification->user->profile->picture)
                <img src="{{ Storage::url($latestClarification->user->profile->picture) }}" class="me-2 sender__profile"
                    alt="">
                @else
                <div class="user__name__initial d-flex align-items-center p-2 me-2 rounded-3 justify-content-center
                                                            text-white"
                    style="background-color: #24695C; height: 30px; width: 30px; border: 2px solid #d9ddd9; font-size: 12px;">
                    {{ $latestClarification->user->profile->getNameInitial() }}</div>
                @endif
                <p class="mb-0" style="font-size: 14px; font-weight: 500;">
                    {{ $latestClarification->user->profile->first_name }}
                    <em>
                        <small class="text-muted" style="font-size: 12px;">(Latest clarification)</small>
                    </em>
                </p>
            </div>
            <p class="mb-0 time__sent">{{ $latestClarification->created_at->diffForHumans(null, true) }} ago</p>
        </div>
        <div class="mb-0 ticket__description">{!! $latestClarification->description !!}</div>
    </div>
    @endif
</div>