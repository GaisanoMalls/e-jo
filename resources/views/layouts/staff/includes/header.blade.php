<div class="navbar navbar-expand-lg bg-white page__main__header justify-content-between">
    <div class="container-fluid px-4">
        <a href="{{ route('staff.dashboard') }}"
            class="navbar-brand d-flex gap-2 align-items-center justify-content-center">
            <img src="{{ asset('images/gmall.png') }}" class="company__logo" alt="GMall Ticketing System">
            <h5 class="mb-0 company__app__name position-relative">
                E-JO
                <span class="position-absolute lbl__system">System</span>
            </h5>
        </a>
        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center hide__in__420px">
                <li class="nav-item dropdown">
                    @livewire('staff.notification.navlink-notification')
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end slideIn animate custom__dropdown__menu">
                        <li>
                            <a class="btn dropdown-item dropdown__menu__items" href="">
                                <div class="d-flex d-flex align-items-center my-1">
                                    <i class="fa-solid fa-user"></i>
                                    <span class="mx-2"></span>
                                    <span>
                                        <small class="dropdown__menu__item__label">My Profile</small>
                                    </span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="btn dropdown-item dropdown__menu__items" href="">
                                <div class="d-flex d-flex align-items-center my-1">
                                    <i class="fa-solid fa-gear"></i>
                                    <span class="mx-2"></span>
                                    <span>
                                        <small class="dropdown__menu__item__label">Account Settings</small>
                                    </span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('auth.logout') }}" method="post">
                                @csrf
                                <button class="btn dropdown-item dropdown__menu__items" type="submit">
                                    <div class="d-flex d-flex align-items-center my-1">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        <span class="mx-2"></span>
                                        <span>
                                            <small class="dropdown__menu__item__label text-danger">Log Out</small>
                                        </span>
                                    </div>
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </div>
            {{-- Mobile Responsive --}}
            @include('layouts.staff.includes.header_mobile')
            {{-- End Mobile Responsive --}}
            <li class="nav-item more__menu" id="toggle__more__menu">
                <a class="nav-link mx-2">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </a>
            </li>
        </div>
    </div>
</div>