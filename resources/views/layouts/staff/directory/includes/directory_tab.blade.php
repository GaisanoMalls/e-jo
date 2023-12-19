<div class="card w-100 border-0 p-0 card__directory directory__tab__container">
    <ul
        class="list-unstyled ps-0 mb-0 d-flex justify-content-lg-start justify-content-md-between border-bottom overflow-scroll ul__tab">
        <li>
            <a href="{{ route('staff.directory.index') }}"
                class="btn d-flex align-items-center border-0 directory__tab__button
                {{ Route::is('staff.directory.index') ? 'directory__active__tab' : '' }}">
                <i class="fa-solid fa-user-shield directory__icon__active__tab directory__active__tab"></i>
                Service Dept. Admins
            </a>
        </li>
        <li>
            <a href="{{ route('staff.directory.approvers') }}"
                class="btn d-flex align-items-center border-0 directory__tab__button
                {{ Route::is('staff.directory.approvers') ? 'directory__active__tab' : '' }}">
                <i class="fa-solid fa-person-circle-check directory__icon__active__tab"></i>
                Approvers
            </a>
        </li>
        <li>
            <a href="{{ route('staff.directory.agents') }}"
                class="btn d-flex align-items-center border-0 directory__tab__button
                {{ Route::is('staff.directory.agents') ? 'directory__active__tab' : '' }}">
                <i class="fa-solid fa-users-gear directory__icon__active__tab"></i>
                Agents
            </a>
        </li>
        <li>
            <a href="{{ route('staff.directory.requesters') }}"
                class="btn d-flex align-items-center border-0 directory__tab__button
                {{ Route::is('staff.directory.requesters') ? 'directory__active__tab' : '' }}">
                <i class="fa-solid fa-user-check directory__icon__active__tab"></i>
                Requesters
            </a>
        </li>
        <li>
            <a href="" class="btn d-flex align-items-center border-0 directory__tab__button">
                <i class="fa-solid fa-people-group directory__icon__active__tab"></i>
                Teams
            </a>
        </li>
    </ul>
</div>
