<div class="card d-flex flex-column roles__permissions__card p-0">
    @include('layouts.staff.system_admin.manage.roles_and_permissions.includes.modal.filter_user_with_roles_modal')
    <div class="roles__permissions__card__header pb-0 pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column me-3">
                <h6 class="card__title">Users with roles</h6>
                <p class="card__description">
                    Find all the users with their associated roles.
                </p>
            </div>
            <div class="d-flex">
                <button type="button" class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                    data-bs-toggle="modal" data-bs-target="#filterUserWithRolesModal">
                    <i class="fa-solid fa-filter"></i>
                    <span class="button__name">Add filter</span>
                </button>
            </div>
        </div>
    </div>
    <div class="roles__permissions__type__card">
        <div class="table-responsive custom__table">
            @if ($users->count() > 0)
            <table class="table table-striped mb-0" id="table">
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            Name
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            BU/Department
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            Branch
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            Role
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            <a href="">
                                <div class="media d-flex align-items-center user__account__media">
                                    <div class="flex-shrink-0">
                                        @if ($user->profile->picture)
                                        <img src="{{ Storage::url($user->profile->picture) }}" alt=""
                                            class="image-fluid user__picture">
                                        @else
                                        @switch($user->role_id)
                                        @case(App\Models\Role::SYSTEM_ADMIN)
                                        <div class="user__name__initial" style="background-color: #D32839;">
                                            {{-- Service Department Admin --}}
                                            {{ $user->profile->getNameInitial() }}
                                        </div>
                                        @break

                                        @case(App\Models\Role::SERVICE_DEPARTMENT_ADMIN)
                                        <div class="user__name__initial" style="background-color: #9DA85C;">
                                            {{-- Service Department Admin --}}
                                            {{ $user->profile->getNameInitial() }}
                                        </div>
                                        @break

                                        @case(App\Models\Role::APPROVER)
                                        <div class="user__name__initial" style="background-color: #3B4053;">
                                            {{-- Approver --}}
                                            {{ $user->profile->getNameInitial() }}
                                        </div>
                                        @break

                                        @case(App\Models\Role::AGENT)
                                        <div class="user__name__initial" style="background-color: #196837;">
                                            {{-- Agent --}}
                                            {{ $user->profile->getNameInitial() }}
                                        </div>
                                        @break

                                        @case(App\Models\Role::USER)
                                        <div class="user__name__initial" style="background-color: #24695C;">
                                            {{-- User/Requester --}}
                                            {{ $user->profile->getNameInitial() }}
                                        </div>
                                        @break
                                        @endswitch
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3 w-100">
                                        <a href="" class="d-flex flex-column gap-1 w-100">
                                            <span class="user__name">{{ $user->profile->getFullName() }}</span>
                                            <small>{{ $user->email }}</small>
                                        </a>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $user->department->name ?? '----' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $user->branch->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $user->role->name }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                <small style="font-size: 14px;">No users with roles.</small>
            </div>
            @endif
        </div>
    </div>
</div>
