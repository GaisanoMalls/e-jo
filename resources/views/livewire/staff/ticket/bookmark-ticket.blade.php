<div>
    <div class="d-flex flex-column">
        @if ($isBookmarked)
            <button
                class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center text-white"
                wire:click="removeBookmark" style="background-color: #196837;">
                <i wire:loading.remove class="fa-solid fa-bookmark"></i>
            </button>
            <small class="ticket__details__topbuttons__label fw-bold">Bookmarked</small>
        @else
            <button
                class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center"
                wire:click="bookmark">
                <i wire:loading.remove class="fa-regular fa-bookmark"></i>
                <div wire:loading wire:target="bookmark">
                    <div class="d-flex align-items-center gap-2">
                        <div class="spinner-border text-success" style="height: 15px; width: 15px;" role="status">
                        </div>
                    </div>
                </div>
            </button>
            <small class="ticket__details__topbuttons__label">Bookmark</small>
        @endif
    </div>
</div>
