<div>
    <div class="row my-5 gap-4">
        @foreach ($feedbacks as $feedback)
            <div wire:key="feedback-{{ $feedback->id }}" class="card border-0 feedback__card">
                <div class="d-flex flex-wrap align-items-center justify-content-between card__header">
                    <div class="d-flex align-items-center feedback__user">
                        <img src="https://samuelsabellano.pythonanywhere.com/media/profile/1_Sabellano_Samuel_Jr_C__DSC9469.JPG"
                            class="user__picture" alt="">
                        <div class="d-flex flex-column">
                            <span class="user__name">
                                {{ auth()->user()->profile->getFullName }}
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
                        @if ($feedback->had_issues_encountered == 'Yes')
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
                    <div class="d-flex align-items-center gap-4">
                        <span class="date">
                            {{ \Carbon\Carbon::parse($feedback->created_at)->format('M d, Y | h:i A') }}
                        </span>
                        @if ($userId == auth()->user()->id)
                            <div class="d-flex align-items-center gap-2 justify-content-end">
                                <button wire:click="deleteFeedback({{ $feedback->id }})" type="button"
                                    class="btn d-flex align-items-center justify-content-center rounded-circle text-danger p-2"
                                    style="height: 1.6rem; width: 1.6rem; font-size: 0.85rem;">
                                    <i class="bi bi-trash" style="margin-top: 0.1rem;"></i>
                                </button>
                                <button wire:click="editFeedback({{ $feedback->id }})" type="button"
                                    class="btn d-flex align-items-center justify-content-center rounded-circle text-danger p-2"
                                    style="height: 1.5rem; width: 1.5rem; font-size: 0.85rem;" data-bs-toggle="modal"
                                    data-bs-target="#editFeedbackModal">
                                    <i class="bi bi-pencil" style="margin-top: 0.1rem;"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Edit Feedback form --}}
    <div wire:ignore.self class="modal fade feedback__modal" id="editFeedbackModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <form wire:submit.prevent="updateFeedback">
                <div class="modal-content feedback__content">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="d-flex flex-column">
                                <div class="mb-1">
                                    <h4 class="fw-bold" style="font-size: 1.3rem; color: #2a4953;">
                                        Edit your feedback
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
                                        <label for="fullname" class="form-label form__field__label">Full
                                            name</label>
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
                                <div class="col-12 d-flex align-items-center justify-content-between">
                                    <button type="submit"
                                        class="btn d-flex align-items-center justify-content-center gap-2 btn__submit">
                                        <span wire:loading wire:target="updateFeedback"
                                            class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true">
                                        </span>
                                        <span wire:loading wire:target="updateFeedback">Updating...</span>
                                        <span wire:loading.remove wire:target="updateFeedback">Update
                                            Feedback</span>
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
        window.addEventListener('close-edit-feedback-modal', () => {
            $(function() {
                $('#editFeedbackModal').modal('hide');
            });
        });
    </script>
@endpush
