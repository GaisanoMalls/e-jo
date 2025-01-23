@php
    use App\Models\Role;
@endphp

<div>
    <div class="sidebar__user text-center">
        <div class="position-relative d-flex justify-content-center">
            @if (auth()->user()->profile->picture)
                <img src="{{ Storage::url(auth()->user()->profile->picture) }}" class="rounded-circle sidebar__userimage" alt="">
            @else
                <div class="d-flex align-items-center justify-content-center rounded-circle sidebar__userinitial">
                    {{ auth()->user()->profile->getNameInitial() }}
                </div>
            @endif
            <div class="sidebar__badge__bottom">
                <span class="badge user__role__badge">
                    @if (auth()->user()->hasRole(Role::SYSTEM_ADMIN))
                        System Admin
                    @endif
                    @if (auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN))
                        Service Department Admin
                    @endif
                    @if (auth()->user()->hasRole(Role::APPROVER))
                        Approver
                    @endif
                    @if (auth()->user()->hasRole(Role::AGENT))
                        Agent
                    @endif
                    @if (auth()->user()->hasRole(Role::USER))
                        User
                    @endif
                </span>
            </div>
        </div>
        <a href="">
            <h6 class="my-3 sidebar__userfullname">
                {{ auth()->user()->profile->getFullName }}
            </h6>
        </a>
        @if (!auth()->user()->hasRole(Role::SYSTEM_ADMIN))
            <p class="sidebar__userdepartment">
                {{ auth()->user()->getBuDepartments() . ' -' }}
                {{ auth()->user()->getBranches() ?? '' }}
            </p>
        @endif
        <div class="mt-3 d-flex staff__ticket__count align-items-center justify-content-around">
            <a href="">
                <li>
                    <span class="counter">4</span>
                    <p class="counter__label">Low</p>
                </li>
            </a>
            <a href="">
                <li>
                    <span class="counter">2</span>
                    <p class="counter__label">Medium</p>
                </li>
            </a>
            <a href="">
                <li>
                    <span class="counter">95</span>
                    <p class="counter__label">High</p>
                </li>
            </a>
            <a href="">
                <li>
                    <span class="counter">2</span>
                    <p class="counter__label">Urgent</p>
                </li>
            </a>
        </div>
    </div>
</div>
