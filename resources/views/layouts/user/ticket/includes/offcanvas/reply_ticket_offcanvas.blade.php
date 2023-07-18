<div class="offcanvas offcanvas-bottom custom__reply__ticket__offcanvas m-auto" tabindex="-1"
    id="offcanvasRequesterReplyTicketForm" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title custom__offcanvas__title" id="offcanvasBottomLabel">Write your reply</h5>
        <button class="btn d-flex align-items-center justify-content-center offcanvas__close__button"
            data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offcanvas-body small">
        @if ($latestReply)
        <div class="mb-4 d-flex flex-column gap-3 reply__ticket__info">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @if ($latestReply->user->profile->picture)
                    <img src="{{ Storage::url($latestReply->user->profile->picture) }}" class="me-2 sender__profile"
                        alt="">
                    @else
                    <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                    text-white" style="background-color: #24695C;">
                        {{ $latestReply->user->profile->getNameInitial() }}</div>
                    @endif
                    <p class="mb-0" style="font-size: 0.813rem; font-weight: 500;">
                        Latest reply from {{ $latestReply->user->profile->first_name }}
                    </p>
                </div>
                <p class="mb-0 time__sent">{{ $latestReply->created_at->diffForHumans(null, true) }} ago</p>
            </div>
            <div class="ticket__description" style="font-size: 13px;">{!! $latestReply->description !!}</div>
        </div>
        @endif
        @include('layouts.user.ticket.includes.forms.reply_ticket_form')
    </div>
</div>
