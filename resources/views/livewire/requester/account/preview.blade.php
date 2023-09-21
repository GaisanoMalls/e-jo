<div class="col-xl-4 col-md-4 mb-3">
    <div class="card custom__card">
        <div class="d-flex flex-column justify-content-center align-items-center py-3">
            @if (auth()->user()->profile->picture)
            <img src="{{ Storage::url(auth()->user()->profile->picture) }}" alt="" class="user__picture">
            @else
            <div class="user__picture d-flex align-items-center p-2 justify-content-center text-white"
                style="background-color: #1A2E35; border: 3px solid #385A64; font-size: 30px;">
                {{ auth()->user()->profile->getNameInitial() }}</div>
            @endif
            <div class="d-flex flex-column mt-3 text-center">
                <h6 class="mb-0 user__full__name">{{ auth()->user()->profile->getFullName() }}</h6>
                <small>{{ auth()->user()->email }}</small>
                <p class="mb-0 mt-3">{{ auth()->user()->department->name ?? '' }}</p>
                <p class="mb-0 mt-4 user__date__joined">
                    Date Joined:
                    <span class="date">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                </p>
            </div>
        </div>
    </div>
</div>