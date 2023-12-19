<nav class="navbar p-2 p-xl-4 p-lg-4 sticky-top position-absolute w-100">
    <div class="container px-2">
        <a href="{{ route('feedback.index') }}"
            class="navbar-brand d-flex gap-2 align-items-center justify-content-center">
            <img src="{{ asset('images/gmall.png') }}" class="company__logo" alt="GMall Ticketing System">
            <h5 class="mb-0 company__app__name position-relative">
                E-JO
                <span class="position-absolute lbl__system">System</span>
            </h5>
        </a>
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center justify-content-center p-1 rounded-5 bg-white" href="#"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                @if (auth()->user()->profile->picture)
                    <img src="{{ Storage::url(auth()->user()->profile->picture) }}" class="feedback__user__picture me-1"
                        alt="">
                @else
                    <div class="d-flex align-items-center p-2 me-2 justify-content-center rounded-circle
                                                     text-white"
                        style="background-color: #24695C; font-size: 14px; height: 35px; width: 35px;">
                        {{ auth()->user()->profile->getNameInitial() }}</div>
                @endif
                <small class="pe-2 fw-semibold">{{ auth()->user()->profile->first_name }}</small>
            </a>
            <ul class="dropdown-menu dropdown-menu-end border-0 feedback__user__dropdown__menu">
                <li><a class="dropdown-item dropdown__item" href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('auth.logout') }}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown__item">Logout</button>
                </li>
                </form>
            </ul>
        </li>
    </div>
</nav>
<section class="banner" style="background-image: url({{ asset('images/feedback.jpg') }});">
    <div class="container banner__container">
        <div class="banner__overlay"></div>
        <div class="col-12">
            <div class="banner__text__container">
                <h1 class="banner__title mb-0">Feedback</h1>
                <div class="d-flex align-items-center gap-2 sub__title__banner" style="color: #54B579;">
                    <div class="d-flex gap-1">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <span class="lbl__rate">Rate our services</span>
                </div>
            </div>
        </div>
    </div>
</section>
