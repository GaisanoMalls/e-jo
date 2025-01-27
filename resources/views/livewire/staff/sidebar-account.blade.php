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
        <h6 class="my-3 sidebar__userfullname">
            {{ auth()->user()->profile->getFullName }}
        </h6>
        @if (!auth()->user()->hasRole(Role::SYSTEM_ADMIN))
            <p class="sidebar__userdepartment">
                {{ auth()->user()->getBuDepartments() . ' -' }}
                {{ auth()->user()->getBranches() ?? '' }}
            </p>
        @endif
        @if ($priorityLevels->isNotEmpty())
            <div class="mt-4 d-flex staff__ticket__count align-items-center justify-content-around">
                @foreach ($priorityLevels as $priority)
                    <a href="{{ route('staff.tickets.priority_level_tickets', $priority->slug) }}">
                        <li>
                            <span class="counter">{{ $this->countTicketsByPriorityLevel($priority) }}</span>
                            <p class="counter__label">{{ $priority->name }}</p>
                        </li>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
