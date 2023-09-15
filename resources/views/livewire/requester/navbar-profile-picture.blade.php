<a wire:poll.visible.7s class="nav-link mx-1" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    @if (auth()->user()->profile->picture)
    <img src="{{ Storage::url(auth()->user()->profile->picture) }}" class="nav__user__picture" alt="">
    @else
    <div class="nav__user__picture d-flex align-items-center p-2 justify-content-center text-white"
        style="background-color: #1A2E35; border: 3px solid #385A64; font-size: 13px;">
        {{ auth()->user()->profile->getNameInitial() }}</div>
    @endif
</a>