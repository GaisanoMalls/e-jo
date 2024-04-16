<div>
    <div class="row my-5 gap-4">
        @if ($toRateTickets->isNotEmpty())
            @foreach ($toRateTickets as $ticket)
                <div wire:key="ticket-{{ $ticket->id }}" class="card border-0 feedback__card"
                    wire:key="ticket-feedback-{{ $ticket->id }}">
                    <div class="d-flex flex-wrap align-items-center justify-content-between card__header">
                        <p class="mb-1 ticket__container">
                            <span class="lbl__ticket__num">Ticket #</span>
                            <span class="ticket__number">{{ $ticket->ticket_number }}</span>
                        </p>
                        @if (is_null($ticket->feedback))
                            <button wire:click="giveFeedback({{ $ticket }})"
                                class="btn btn-sm d-flex align-items-center gap-1 btn__give__rate" data-bs-toggle="modal"
                                data-bs-target="#ticketFeedbackModal">
                                <i class="fa-solid fa-star"></i>
                                Give Feedback
                            </button>
                        @else
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-end gap-1 stars__container">
                                    @for ($rating = 0; $rating < $ticket->feedback->rating; $rating++)
                                        <i class="fa-solid fa-star filled"></i>
                                    @endfor
                                    @for ($i = $ticket->feedback->rating; $i < 5; $i++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                </div>
                                <small class="text-end text-muted mt-1" style="font-size: 0.8rem;">
                                    @if ($ticket->feedback->rating == 1)
                                        Terrible
                                    @endif
                                    @if ($ticket->feedback->rating == 2)
                                        Bad
                                    @endif
                                    @if ($ticket->feedback->rating == 3)
                                        Good
                                    @endif
                                    @if ($ticket->feedback->rating == 4)
                                        Very Good
                                    @endif
                                    @if ($ticket->feedback->rating == 5)
                                        Excellent
                                    @endif
                                </small>
                            </div>
                        @endif
                    </div>
                    <div class="card__body bg-white p-0">
                        <div class="d-flex flex-column gap-4">
                            <div class="mb-0">
                                <h6 class="mb-1 ticket__title">{{ $ticket->subject }}</h6>
                                <div class="ticket__description">
                                    {!! $ticket->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between feedback__footer">
                        <div class="d-flex align-items-center gap-1 replies__count">
                            <i class="fa-solid fa-comment-dots"></i>
                            {{ $ticket->replies->count() }}
                        </div>
                        <span class="date">
                            Closed:
                            {{ \Carbon\Carbon::parse($ticket->updated_at)->format('M d, Y | h:i A') }}
                        </span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Feedback form --}}
    <div wire:ignore.self class="modal fade feedback__modal" id="ticketFeedbackModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <form wire:submit.prevent="submitFeedback">
                <div class="modal-content feedback__content">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="d-flex flex-column">
                                <div class="mb-1">
                                    <h4 class="fw-bold" style="font-size: 1.3rem; color: #2a4953;">
                                        Fill in the form to submit your feedback
                                    </h4>
                                    <p class="fw-normal mb-0"
                                        style="color: #717D8D; font-size: 13px; line-height: 1.1rem;">
                                        We read every feedback we get and we take it very seriously
                                    </p>
                                </div>
                                <img class="feedback__form__pic" src="{{ asset('images/feedback_form.jpg') }}"
                                    alt="">
                                @if ($ticket)
                                    <div class="d-flex flex-column gap-1">
                                        <a href="{{ route('user.ticket.view_ticket', $ticket) }}"
                                            class="ticket__number text-center">{{ $ticketNumber }}</a>
                                        <small class="text-uppercase text-center text-muted" style="font-size: 0.7rem;">
                                            Ticket Number
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row form__inputs">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fullname" class="form-label form__field__label">Full name</label>
                                        <input type="text" class="form-control form__field" wire:model="fullName"
                                            id="fullname" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label form__field__label">Email
                                            address</label>
                                        <input type="email" class="form-control form__field" wire:model="email"
                                            id="email" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3 rounded-3 @error('rating') bg-light py-2 px-3 @enderror">
                                        <label for="serviceRating" class="form-label form__field__label">
                                            Service Rating
                                        </label>
                                        <div class="d-flex flex-column gap-1">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="form-check d-flex gap-2 align-items-center">
                                                    <input wire:model="rating" value="1"
                                                        class="form-check-input radio__button" type="radio"
                                                        id="terrible">
                                                    <label class="form-check-label check__label" for="terrible">
                                                        Terrible
                                                    </label>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-end gap-1 stars__container">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="form-check d-flex gap-2 align-items-center">
                                                    <input wire:model="rating" value="2"
                                                        class="form-check-input radio__button" type="radio"
                                                        id="bad">
                                                    <label class="form-check-label check__label" for="bad">
                                                        Bad
                                                    </label>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-end gap-1 stars__container">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="form-check d-flex gap-2 align-items-center">
                                                    <input wire:model="rating" value="3"
                                                        class="form-check-input radio__button" type="radio"
                                                        id="good">
                                                    <label class="form-check-label check__label" for="good">
                                                        Good
                                                    </label>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-end gap-1 stars__container">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="form-check d-flex gap-2 align-items-center">
                                                    <input wire:model="rating" value="4"
                                                        class="form-check-input radio__button" type="radio"
                                                        id="very_good">
                                                    <label class="form-check-label check__label" for="very_good">
                                                        Very Good
                                                    </label>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-end gap-1 stars__container">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="form-check d-flex gap-2 align-items-center">
                                                    <input wire:model="rating" value="5"
                                                        class="form-check-input radio__button" type="radio"
                                                        id="excellent">
                                                    <label class="form-check-label check__label" for="excellent">
                                                        Excellent
                                                    </label>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-end gap-1 stars__container">
                                                    <i class="fa-solid fa-star filled"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                    <i class="fa-solid fa-star filled"></i>
                                                </div>
                                            </div>
                                        </div>
                                        @error('rating')
                                            <small class="text-danger feedback__error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div
                                        class="mb-3 rounded-3 @error('had_issues_encountered') bg-light py-2 px-3 @enderror">
                                        <label class="form-label form__field__label">Have you experience any
                                            technical problems while using the ticketing system?</label>
                                        <div class="d-flex flex-column gap-1">
                                            <div class="form-check d-flex gap-2 align-items-center">
                                                <input wire:model="had_issues_encountered" value="Yes"
                                                    class="form-check-input radio__button" type="radio"
                                                    id="yes">
                                                <label class="form-check-label check__label" for="yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="form-check d-flex gap-2 align-items-center">
                                                <input wire:model="had_issues_encountered" value="No"
                                                    class="form-check-input radio__button" type="radio"
                                                    id="no">
                                                <label class="form-check-label check__label" for="no">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                        @error('had_issues_encountered')
                                            <small class="text-danger feedback__error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3 rounded-3 @error('feedback') bg-light py-2 px-3 @enderror">
                                        <label for="feedback" class="form-label form__field__label">
                                            Feedback
                                        </label>
                                        <textarea class="form-control form__field custom__textarea" wire:model="feedback"
                                            placeholder="Type your feedback here" id="feedback"></textarea>
                                        @error('feedback')
                                            <small class="text-danger feedback__error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="suggestion" class="form-label form__field__label">
                                            Suggestions
                                            <span>
                                                <small>(Optional)</small>
                                            </span>
                                        </label>
                                        <textarea class="form-control form__field custom__textarea" wire:model="suggestion" id="suggestion"
                                            placeholder="Type your suggestions here" id="suggestions">{{ old('suggestion') }}</textarea>
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div
                                        class="mb-3 rounded-3 @error('accepted_privacy_policy') bg-light py-2 px-3 @enderror">
                                        <div class="form-check">
                                            <input class="form-check-input check__privacy__policy" type="checkbox"
                                                wire:model="accepted_privacy_policy" id="privacy_policy"
                                                style="margin-top: 5px;">
                                            <label class="form-check-label check__label" for="privacy_policy">
                                                I have read and accept the Privacy Policy.
                                            </label>
                                        </div>
                                        @error('accepted_privacy_policy')
                                            <small class="text-danger feedback__error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="col-12 d-flex align-items-center justify-content-between">
                                    <button type="submit"
                                        class="btn d-flex align-items-center justify-content-center gap-2 btn__submit">
                                        <span wire:loading wire:target="submitFeedback"
                                            class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true">
                                        </span>
                                        <span wire:loading wire:target="submitFeedback">Submitting...</span>
                                        <span wire:loading.remove wire:target="submitFeedback">Submit Feedback</span>
                                    </button>
                                    <button wire:click="cancel" type="button" class="btn btn-sm p-1"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('custom-js')
    <script>
        window.addEventListener('close-feedback-modal', () => {
            $(function() {
                $('#ticketFeedbackModal').modal('hide');
            });
        });
    </script>
@endpush
