<div class="card w-100 border-0 p-0 card__settings settings__tab__container" id="settingsTabContainer">
    <div class="d-flex align-items-center border-bottom justify-content-between">
        <ul
            class="list-unstyled ps-0 mb-0 d-flex justify-content-lg-start justify-content-md-between overflow-scroll ul__tab">
            <li>
                <a href="{{ route('staff.manage.roles_and_permissions.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.roles_and_permissions.*') ? 'settings__active__tab' : '' }}">
                    {{-- <i class="fa-solid fa-user-lock settings__icon__active__tab"></i> --}}
                    Roles & Permissions
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.user_account.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.user_account.*') ? 'settings__active__tab' : '' }}">
                    {{-- <i class="bi bi-people-fill settings__icon__active__tab"></i> --}}
                    Accounts
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.service_level_agreements.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.service_level_agreements.index') ? 'settings__active__tab' : '' }}">
                    {{-- <i class="fa-solid fa-handshake settings__icon__active__tab"></i> --}}
                    SLA Plans
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.branch.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.branch.*') ? 'settings__active__tab' : '' }}">
                    {{-- <i class="bi bi-geo-alt-fill settings__icon__active__tab"></i> --}}
                    Branches
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.bu_department.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.bu_department.*') ? 'settings__active__tab' : '' }}">
                    {{-- <i class="bi bi-buildings-fill settings__icon__active__tab"></i> --}}
                    BU/Departments
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.service_department.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.service_department.*') ? 'settings__active__tab' : '' }}">
                    {{-- <i class="fa-solid fa-gears settings__icon__active__tab"></i> --}}
                    Service Departments
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.team.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.team.*') ? 'settings__active__tab' : '' }}">
                    {{-- <i class="fa-solid fa-people-group settings__icon__active__tab"></i> --}}
                    Teams
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.help_topic.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.help_topic.*') ? 'settings__active__tab' : '' }}">
                    {{-- <i class="bi bi-question-circle-fill settings__icon__active__tab"></i> --}}
                    Help Topics
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.tag.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.tag.*') ? 'settings__active__tab' : '' }}">
                    {{-- <i class="bi bi-tags-fill settings__icon__active__tab"></i> --}}
                    Tags
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.ticket_statuses.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.ticket_statuses.*') ? 'settings__active__tab' : '' }}">
                    {{-- <i class="fa-solid fa-clipboard-check settings__icon__active__tab"></i> --}}
                    Ticket Status
                </a>
            </li>
        </ul>
        <div class="ms-3 d-flex align-items-center justify-content-center shadow rounded-circle"
            style="min-height: 30px; min-width: 30px;"
            data-tooltip="To scroll horizontally, hold down the Shift key and then scroll" data-tooltip-position="top"
            data-tooltip-font-size="0.75rem" data-tooltip-max-width="200px" data-tooltip-additional-classes="rounded-3">
            <i class="bi bi-info-circle"></i>
        </div>
    </div>
</div>
