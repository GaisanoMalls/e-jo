<section class="tab__container d-flex justify-content-center">
    <div class="row mx-0">
        <ul class="d-flex justify-content-center tab__header">
            <li class="nav-item">
                <a class="nav-link d-flex flex-column align-items-center justify-content-center gap-1 tab__link
                    {{ Route::is('feedback.to_rate') ? 'active' : '' }}"
                    href="{{ route('feedback.to_rate') }}">
                    <i class="fa-solid fa-medal"></i>
                    For feedback
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex flex-column align-items-center justify-content-center gap-1 tab__link
                    {{ Route::is('feedback.reviews') ? 'active' : '' }}"
                    href="{{ route('feedback.reviews') }}">
                    <i class="fa-solid fa-comment-dots"></i>
                    My Feedbacks
                </a>
            </li>
        </ul>
    </div>
</section>
