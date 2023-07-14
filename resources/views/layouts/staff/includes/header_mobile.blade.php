<div class="show__in__420px d-none">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                <form action="{{ route('auth.staff.logout_staff') }}" method="post">
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
