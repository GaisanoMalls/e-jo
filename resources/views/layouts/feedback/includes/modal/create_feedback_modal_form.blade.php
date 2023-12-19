<form action="{{ route('feedback.store') }}" method="post">
    @csrf
    <div class="modal fade feedback__modal" id="ticketFeedbackModal{{ $ticket->id }}" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content feedback__content">
                <div class="row">
                    <div class="col-md-5">
                        <div class="d-flex flex-column">
                            <div class="mb-1">
                                <h4 class="fw-bold" style="font-size: 1.3rem; color: #2a4953;">Fill in the form to
                                    submit
                                    your feedback
                                </h4>
                                <p class="fw-normal mb-0" style="color: #717D8D; font-size: 13px; line-height: 1.1rem;">
                                    We read every
                                    feedback
                                    we get and we take
                                    it very
                                    seriously</p>
                            </div>
                            <img class="feedback__form__pic" src="{{ asset('images/feedback_form.jpg') }}"
                                alt="">
                            <h5 class="mb-4 ticket__number text-center">{{ $ticket->ticket_number }}</h5>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row form__inputs">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label form__field__label">Full name</label>
                                    <input type="text" class="form-control form__field" name="fullname"
                                        id="fullname" readonly value="{{ auth()->user()->profile->getFullName() }}"
                                        placeholder="Type here...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label form__field__label">Email
                                        address</label>
                                    <input type="email" class="form-control form__field" name="email" id="email"
                                        readonly value="{{ auth()->user()->email }}" placeholder="Type here...">
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
                                                <input class="form-check-input radio__button" type="radio"
                                                    name="rating" id="terrible" value="1"
                                                    {{ old('rating') == 1 ? 'checked' : '' }}>
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
                                                <input class="form-check-input radio__button" type="radio"
                                                    name="rating" id="bad" value="2"
                                                    {{ old('rating') == 2 ? 'checked' : '' }}>
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
                                                <input class="form-check-input radio__button" type="radio"
                                                    name="rating" id="good" value="3"
                                                    {{ old('rating') == 3 ? 'checked' : '' }}>
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
                                                <input class="form-check-input radio__button" type="radio"
                                                    name="rating" id="very_good" value="4"
                                                    {{ old('rating') == 4 ? 'checked' : '' }}>
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
                                                <input class="form-check-input radio__button" type="radio"
                                                    name="rating" id="excellent" value="5"
                                                    {{ old('rating') == 5 ? 'checked' : '' }}>
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
                                    @error('rating', 'storeFeedback')
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
                                            <input class="form-check-input radio__button" type="radio"
                                                name="had_issues_encountered" value="Yes" id="yes"
                                                {{ old('had_issues_encountered') == 'Yes' ? 'checked' : '' }}>
                                            <label class="form-check-label check__label" for="yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check d-flex gap-2 align-items-center">
                                            <input class="form-check-input radio__button" type="radio"
                                                name="had_issues_encountered" value="No" id="no"
                                                {{ old('had_issues_encountered') == 'No' ? 'checked' : '' }}>
                                            <label class="form-check-label check__label" for="no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    @error('had_issues_encountered', 'storeFeedback')
                                        <small class="text-danger feedback__error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3 rounded-3 @error('description') bg-light py-2 px-3 @enderror">
                                    <label for="feedback" class="form-label form__field__label">Additional
                                        feedback</label>
                                    <textarea class="form-control form__field custom__textarea" name="description" placeholder="Type your feedback here"
                                        id="feedback">{{ old('description') }}</textarea>
                                    @error('description', 'storeFeedback')
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
                                        Suggestions/recommendations
                                        <span>
                                            <small>(Optional)</small>
                                        </span>
                                    </label>
                                    <textarea class="form-control form__field custom__textarea" name="suggestion"
                                        placeholder="Type your suggestions here" id="suggestions">{{ old('suggestion') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div
                                    class="mb-3 rounded-3 @error('accepted_privacy_policy') bg-light py-2 px-3 @enderror">
                                    <div class="form-check">
                                        <input class="form-check-input check__privacy__policy" type="checkbox"
                                            name="accepted_privacy_policy" id="privacy_policy"
                                            style="margin-top: 5px;" value="1"
                                            {{ old('accepted_privacy_policy') == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label check__label" for="privacy_policy">
                                            I have read and accept the Privacy Policy.
                                        </label>
                                    </div>
                                    @error('accepted_privacy_policy', 'storeFeedback')
                                        <small class="text-danger feedback__error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-between">
                                <button type="submit" class="btn  btn__submit">Submit Feedback</button>
                                <button type="button" class="btn btn-sm p-1" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
