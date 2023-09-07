<div class="offcanvas offcanvas-bottom custom__reply__ticket__offcanvas m-auto" tabindex="-1"
    id="offcanvasClarificationModal" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title custom__offcanvas__title" id="offcanvasBottomLabel">Write your reply</h5>
        <button class="btn d-flex align-items-center justify-content-center offcanvas__close__button"
            data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offcanvas-body small">
        @if ($latestClarification)
        <div class="mb-4 d-flex flex-column gap-3 reply__ticket__info">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @if ($latestClarification->user !== null && $latestClarification->user->profile->picture)
                    <img src="{{ Storage::url($latestClarification->user->profile->picture) }}"
                        class="me-2 sender__profile" alt="">
                    @else
                    <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                    text-white" style="background-color: #24695C;">
                        {{ $latestClarification->user !== null ?? $latestClarification->user->profile->getNameInitial()
                        }}</div>
                    @endif
                    <p class="mb-0" style="font-size: 0.813rem; font-weight: 500;">
                        Latest reply from
                        {{ $latestClarification->user !== null ?? $latestClarification->user->profile->first_name }}
                    </p>
                </div>
                <p class="mb-0 time__sent">{{ $latestClarification->created_at->diffForHumans(null, true) }} ago</p>
            </div>
            <div class="ticket__description" style="font-size: 13px;">{!! $latestClarification->description !!}</div>
        </div>
        @endif
        <form action="{{ route('staff.service_dept_head.level_1_approval.send_clarification', $ticket->id) }}"
            method="post" enctype="multipart/form-data">
            @csrf
            <textarea id="myeditorinstance" name="description" placeholder="Type here...">
                {{ old('description') }}
            </textarea>
            @error('description', 'storeTicketClarification')
            <span class="error__message">
                <i class="fa-solid fa-triangle-exclamation"></i>
                {{ $message }}
            </span>
            @enderror
            <div class="mt-1 d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex flex-column gap-1">
                    <input class="form-control ticket__file__input w-auto my-3" type="file" name="clarificationFiles[]"
                        id="ticketFile" multiple>
                    @error('clarificationFiles.*', 'storeTicketClarification')
                    <span class="error__message">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <button type="submit"
                    class="btn my-3 d-flex align-items-center justify-content-center gap-2 btn__send__ticket__reply">
                    Send
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>