<div class="card d-flex flex-column mb-4 roles__permissions__card">
    <div class="roles__permissions__card__header">
        <div class="row">
            <div class="col-md-6">
                <h6 class="card__title">Account with roles</h6>
                <p class="card__description">
                    Assigned to Users which defines the level of access such as Approver,
                    Department Admin,
                    Agent,
                    and User/Requeser), and the types of transactions and services that can be accessed by
                    the User.
                </p>
            </div>
        </div>
    </div>
    <div class="roles__permissions__type__card">
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-md-6 col-lg-6 mt-3">
                <div class="card d-flex flex-column gap-3 roles__permission__type__card">
                    <div
                        class="d-flex align-items-center justify-content-between roles__permission__type__card__header">
                        <h6 class="mb-0 account__number">{{ $approvers->count() }} Accounts</h6>
                        <div class="d-flex align-items-center flex-nowrap user__picture__container">
                            @foreach ($approvers->take($profilePicLimit) as $approver)
                                @if ($approver->profile->picture)
                                    <img src="{{ Storage::url($approver->profile->picture) }}" class="picture"
                                        alt="">
                                @else
                                    <div class="picture initial__as__picture" style="background-color: #3B4053;">
                                        {{ $approver->profile->getNameInitial() }}
                                    </div>
                                @endif
                            @endforeach
                            @if ($restApproverAccounts !== 0)
                                <small class="count__rest">+{{ $restApproverAccounts }}</small>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('staff.manage.user_account.approvers') }}"
                        class="d-flex align-items-center gap-2 card__name">
                        Approver
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-md-6 col-lg-6 mt-3">
                <div class="card d-flex flex-column gap-3 roles__permission__type__card">
                    <div
                        class="d-flex align-items-center justify-content-between roles__permission__type__card__header">
                        <h6 class="mb-0 account__number">{{ $serviceDeptAdmins->count() }} Accounts</h6>
                        <div class="d-flex align-items-center flex-nowrap user__picture__container">
                            @foreach ($serviceDeptAdmins->take($profilePicLimit) as $serviceDeptAdmin)
                                @if ($serviceDeptAdmin->profile->picture)
                                    <img src="{{ Storage::url($serviceDeptAdmin->profile->picture) }}" class="picture"
                                        alt="">
                                @else
                                    <div class="picture initial__as__picture" style="background-color: #9DA85C;">
                                        {{ $serviceDeptAdmin->profile->getNameInitial() }}
                                    </div>
                                @endif
                            @endforeach
                            @if ($restServiceDeptAdminAccounts !== 0)
                                <small class="count__rest">+{{ $restServiceDeptAdminAccounts }}</small>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('staff.manage.user_account.service_department_admins') }}"
                        class="d-flex align-items-center gap-2 card__name">
                        Service Dept. Admin
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-md-6 col-lg-6 mt-3">
                <div class="card d-flex flex-column gap-3 roles__permission__type__card">
                    <div
                        class="d-flex align-items-center justify-content-between roles__permission__type__card__header">
                        <h6 class="mb-0 account__number">{{ $agents->count() }} Accounts</h6>
                        <div class="d-flex align-items-center flex-nowrap user__picture__container">
                            @foreach ($agents->take($profilePicLimit) as $agent)
                                @if ($agent->profile->picture)
                                    <img src="{{ Storage::url($agent->profile->picture) }}" class="picture"
                                        alt="">
                                @else
                                    <div class="picture initial__as__picture" style="background-color: #196837;">
                                        {{ $agent->profile->getNameInitial() }}
                                    </div>
                                @endif
                            @endforeach
                            @if ($restAgentAccounts !== 0)
                                <small class="count__rest">+{{ $restAgentAccounts }}</small>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('staff.manage.user_account.agents') }}"
                        class="d-flex align-items-center gap-2 card__name">
                        Agent
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-md-6 col-lg-6 mt-3">
                <div class="card d-flex flex-column gap-3 roles__permission__type__card">
                    <div
                        class="d-flex align-items-center justify-content-between roles__permission__type__card__header">
                        <h6 class="mb-0 account__number">{{ $users->count() }} Accounts</h6>
                        <div class="d-flex align-items-center flex-nowrap user__picture__container">
                            @foreach ($users->take($profilePicLimit) as $user)
                                @if ($user->profile->picture)
                                    <img src="{{ Storage::url($user->profile->picture) }}" class="picture"
                                        alt="">
                                @else
                                    <div class="picture initial__as__picture" style="background-color: #24695C;">
                                        {{ $user->profile->getNameInitial() }}
                                    </div>
                                @endif
                            @endforeach
                            @if ($restUserAccounts !== 0)
                                <small class="count__rest">+{{ $restUserAccounts }}</small>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('staff.manage.user_account.users') }}"
                        class="d-flex align-items-center gap-2 card__name">
                        User/Requester
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
