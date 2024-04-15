<div>
    <div class="row my-5 gap-4">
        @foreach ($feedbacks as $feedback)
            <div class="card border-0 feedback__card">
                <div class="d-flex flex-wrap align-items-center justify-content-between card__header">
                    <div class="d-flex align-items-center feedback__user">
                        <img src="https://samuelsabellano.pythonanywhere.com/media/profile/1_Sabellano_Samuel_Jr_C__DSC9469.JPG"
                            class="user__picture" alt="">
                        <div class="d-flex flex-column">
                            <span class="user__name">
                                {{ auth()->user()->profile->getFullName() }}
                                @if (auth()->user()->id === $feedback->user_id)
                                    <small style="font-size: 11px; color: #808080;">
                                        <i class="fa-solid fa-check"></i>
                                    </small>
                                @endif
                            </span>
                            <span class="text-muted" style="font-size: 0.8rem; line-height: 1.2;">
                                {{ auth()->user()->email }}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center gap-1 ratings">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $feedback->rating)
                                    <i class="fa-solid fa-star filled"></i>
                                @else
                                    <i class="fa-solid fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <small class="text-end text-muted mt-1" style="font-size: 0.8rem;">
                            @if ($feedback->rating == 1)
                                Terrible
                            @endif
                            @if ($feedback->rating == 2)
                                Bad
                            @endif
                            @if ($feedback->rating == 3)
                                Good
                            @endif
                            @if ($feedback->rating == 4)
                                Very Good
                            @endif
                            @if ($feedback->rating == 5)
                                Excellent
                            @endif
                        </small>
                    </div>
                </div>
                <div class="card__body">
                    <div class="d-flex flex-column gap-4">
                        @if ($feedback->description)
                            <div class="mb-0">
                                <h6 class="mb-1 body__section__title">Feedback</h6>
                                <div class="mb-0 long__desc">
                                    {{ $feedback->description }}
                                </div>
                            </div>
                        @endif
                        @if ($feedback->suggestion)
                            <div class="mb-0">
                                <h6 class="mb-1 body__section__title">Suggestions/recommendations</h6>
                                <div class="mb-0 long__desc">
                                    {{ $feedback->suggestion }}
                                </div>
                            </div>
                        @endif
                        @if ($feedback->had_issues_encountered)
                            <div class="d-flex align-items-center rounded-2 justify-content-center gap-2"
                                style="background-color: #e2e2e1; width: 11.3rem; padding: 2px 8px;">
                                <i class="bi bi-info-circle"></i>
                                <small style="color: #585858;">Had issues encountered</small>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between feedback__footer">
                    <a href="{{ route('user.ticket.view_ticket', $feedback->ticket->id) }}" class="ticket">
                        <span>Ticket #</span>
                        <span class="number">{{ $feedback->ticket->ticket_number }}</span>
                    </a>
                    <div class="d-flex align-items-center">
                        <span class="date">
                            {{ \Carbon\Carbon::parse($feedback->created_at)->format('M d, Y | h:i A') }}
                        </span>
                        <button type="button"
                            class="btn ms-3 d-flex align-items-center justify-content-center rounded-circle text-danger p-2"
                            style="height: 1.6rem; width: 1.6rem;">
                            <i class="bi bi-trash" style="margin-top: 0.1rem;"></i>
                        </button>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
