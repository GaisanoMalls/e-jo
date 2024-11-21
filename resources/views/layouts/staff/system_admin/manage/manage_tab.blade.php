<div class="card w-100 border-0 p-0 card__settings settings__tab__container" id="settingsTabContainer">
    <div class="d-flex align-items-center border-bottom justify-content-between">
        <ul
            class="list-unstyled ps-0 mb-0 d-flex justify-content-lg-start justify-content-md-between overflow-scroll ul__tab">
            <li>
                <a href="{{ route('staff.manage.roles_and_permissions.index') }}"
                    class="btn d-flex align-items-center border-0 position-relative settings__tab__button
                    {{ Route::is('staff.manage.roles_and_permissions.*') ? 'settings__active__tab' : '' }}">
                    <span class="rounded-2" style="padding: 0.4rem 0;">
                        Roles & Permissions
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.user_account.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.user_account.*') ? 'settings__active__tab' : '' }}">
                    <span class="rounded-2" style="padding: 0.4rem 0;">
                        Accounts
                    </span>
                </a>
            </li>

            <li>
                <a href="{{ route('staff.manage.branch.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.branch.*') ? 'settings__active__tab' : '' }}">
                    <span class="rounded-2" style="padding: 0.4rem 0;">
                        Branches
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.bu_department.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.bu_department.*') ? 'settings__active__tab' : '' }}">
                    <span class="rounded-2" style="padding: 0.4rem 0;">
                        BU/Departments
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.service_department.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.service_department.*') ? 'settings__active__tab' : '' }}">
                    <span class="rounded-2" style="padding: 0.4rem 0;">
                        Service Departments
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.team.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.team.*') ? 'settings__active__tab' : '' }}">
                    <span class="rounded-2" style="padding: 0.4rem 0;">
                        Teams
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.help_topic.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.help_topic.*') ? 'settings__active__tab' : '' }}">
                    <span class="rounded-2" style="padding: 0.4rem 0;">
                        Help Topics
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.service_level_agreements.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.service_level_agreements.index') ? 'settings__active__tab' : '' }}">
                    <span class="rounded-2" style="padding: 0.4rem 0;">
                        SLA Plans
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.tag.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.tag.*') ? 'settings__active__tab' : '' }}">
                    <span class="rounded-2" style="padding: 0.4rem 0;">
                        Tags
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.manage.ticket_statuses.index') }}"
                    class="btn d-flex align-items-center border-0 settings__tab__button
                    {{ Route::is('staff.manage.ticket_statuses.*') ? 'settings__active__tab' : '' }}">
                    <span class="rounded-2" style="padding: 0.4rem 0;">
                        Ticket Status
                    </span>
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
