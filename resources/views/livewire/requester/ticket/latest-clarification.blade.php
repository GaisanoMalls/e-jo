<div>
    @if ($latestClarification)
        <div class="my-4 d-flex flex-column gap-3 reply__ticket__info">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @if ($latestClarification->user !== null && $latestClarification->user->profile->picture)
                        <img src="{{ Storage::url($latestClarification->user->profile->picture) }}"
                            class="me-2 sender__profile" alt="">
                    @else
                        <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                                                text-white"
                            style="background-color: #24695C;">
                            {{ $latestClarification->user->profile->getNameInitial() }}
                        </div>
                    @endif
                    <p class="mb-0" style="font-size: 14px; font-weight: 500;">
                        {{ $latestClarification->user->profile->first_name }}
                        <em>
                            <small class="text-muted" style="font-size: 12px;">(Latest reply)</small>
                        </em>
                    </p>
                </div>
                <p class="mb-0 time__sent">{{ $latestClarification->created_at->diffForHumans(null, true) }} ago
                </p>
            </div>
            <div class="ticket__description" style="font-size: 13px;">
                {!! $latestClarification->description !!}
            </div>
        </div>
    @endif
</div>
