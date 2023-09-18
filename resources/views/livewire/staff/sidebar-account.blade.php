<div>
    <div class="sidebar__user text-center">
        <div class="position-relative d-flex justify-content-center">
            @if (auth()->user()->profile->picture)
            <img src="{{ Storage::url(auth()->user()->profile->picture) }}" class="rounded-circle sidebar__userimage"
                alt="">
            @else
            <div class="d-flex align-items-center justify-content-center rounded-circle sidebar__userinitial">
                {{ auth()->user()->profile->getNameInitial() }}
            </div>
            @endif
            <div class="sidebar__badge__bottom">
                <span class="badge user__role__badge">
                    @switch(auth()->user()->role_id)
                    @case(App\MOdels\Role::SYSTEM_ADMIN)
                    System Admin
                    @break
                    @case(App\MOdels\Role::SERVICE_DEPARTMENT_ADMIN)
                    Service Department Admin
                    @break
                    @default
                    @case(App\MOdels\Role::APPROVER)
                    Approver
                    @break
                    @case(App\MOdels\Role::AGENT)
                    Agent
                    @break
                    @case(App\MOdels\Role::USER)
                    User
                    @break
                    @endswitch
                </span>
            </div>
        </div>
        <div class="my-3">
            <a href="">
                <h6 class="sidebar__userfullname">
                    {{ auth()->user()->profile->getFullName() }}
                </h6>
            </a>
            @if (auth()->user()->department)
            @switch(auth()->user()->role_id)
            @case(App\MOdels\Role::SERVICE_DEPARTMENT_ADMIN)
            <small class="fw-semibold px-3 py-2 rounded-5" style="font-size: 12px; background-color:
                    #9DA85C; color: #FFFFFF; box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem
                    0.25rem -0.0625rem rgba(20, 20, 20,
                    0.07);" title="{{ auth()->user()->department->name ?? '' }}"">
                    {{ Str::limit(auth()->user()->department->name, '30') ?? '' }}
                </small>
                @break
                @case(App\MOdels\Role::APPROVER)
                <small class=" fw-semibold px-3 py-2 rounded-5" style="font-size: 12px; background-color:
                    #3B4053; color: #FFFFFF; box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem
                    0.25rem -0.0625rem rgba(20, 20, 20,
                    0.07);" title="{{ auth()->user()->department->name ?? '' }}"">
                    {{ Str::limit(auth()->user()->department->name, '30') ?? '' }}
                </small>
                @break
                @case(App\MOdels\Role::AGENT)
                <small class=" fw-semibold px-3 py-2 rounded-5" style="font-size: 12px; background-color:
                    #196837; color: #FFFFFF; box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem
                    0.25rem -0.0625rem rgba(20, 20, 20,
                    0.07);" title="{{ auth()->user()->department->name ?? '' }}">
                {{ Str::limit(auth()->user()->department->name, '30') ?? '' }}
            </small>
            @break
            @case(App\MOdels\Role::USER)
            <small class="fw-semibold px-3 py-2 rounded-5" style="font-size: 12px; background-color:
                    #24695C; color: #FFFFFF; box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem
                    0.25rem -0.0625rem rgba(20, 20, 20,
                    0.07);" title="{{ auth()->user()->department->name ?? '' }}"">
                    {{ Str::limit(auth()->user()->department->name, '30') ?? '' }}
                </small>
                @break
                @default
                <small class=" fw-semibold px-3 py-2 rounded-5" style="font-size: 12px; background-color:
                    #F2F2F2; color: #5b5943; box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem
                    0.25rem -0.0625rem rgba(20, 20, 20,
                    0.07);">
                {{ Str::limit(auth()->user()->department->name, '30') ?? '' }}
            </small>
            @endswitch
            @endif
        </div>
        <p class=" sidebar__userdepartment">
            {{ auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN
            ? Str::limit(auth()->user()->getServiceDepartments() , 30) . " -"
            : (auth()->user()->role_id !== App\Models\Role::SYSTEM_ADMIN
            ? auth()->user()->serviceDepartment->name . " -" : '')
            ?? '' }}
            {{ auth()->user()->branch->name ?? '' }}
        </p>
        <div class="mt-3 d-flex staff__ticket__count justify-content-center">
            <li>
                <span class="counter">9</span>
                <p class="counter__label">Claimed</p>
            </li>
            <li>
                <span class="counter">95</span>
                <p class="counter__label">Answered </p>
            </li>
            <li>
                <span class="counter">2</span>
                <p class="counter__label">Resolved</p>
            </li>
        </div>
    </div>
</div>