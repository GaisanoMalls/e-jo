@if ($today_announcements)
<div class="row">
    <div class="row">
        <div class="col-xxl-1 col-lg-2 col-md-2 col-3">
            {{-- Leave it empty! Don't remove --}}
        </div>
        <div class="col-xxl-11 col-lg-10 col-md-10 col-9">
            <h6 style="margin-left: 2px; margin-bottom: 30px; color: #3D3D3D;">Today</h6>
        </div>
    </div>
    @foreach ($today_announcements as $announcement)
    @include('layouts.staff.announcement.includes.modal.confirm_delete_announcement')
    @include('layouts.staff.announcement.includes.modal.edit_announcement_modal_form')
    <div class="row mb-3">
        <div class="col-xxl-1 col-lg-2 col-md-2 col-3">
            <div class="day__year d-flex flex-column">
                <p class="month">
                    {{ \Carbon\Carbon::parse($announcement->created_at)->isoFormat('MMM DD YYYY') }}
                </p>
                <small class="time">{{ date('h:i A', strtotime($announcement->created_at)) }}</small>
            </div>
        </div>
        <div class="col-xxl-11 col-lg-10 col-md-10 col-9">
            <div class="d-flex flex-column gap-3">
                <div class="announcement__content">
                    <div class="d-flex align-items-center justify-content-between flex-nowrap">
                        <h6 class="announcement__title">{{ $announcement->title }}</h6>
                        <div class="btn-group dropstart">
                            <button type="button" class="btn d-flex align-items-center justify-content-center btn__menu"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu border-0 custom__dropdown__menu">
                                <li>
                                    <button type="button"
                                        class="dropdown-item d-flex align-items-center custom__dropdown__item"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editAnnouncement{{ $announcement->id }}">
                                        <i class="fa-solid fa-pen me-3"></i>
                                        Edit
                                    </button>
                                </li>
                                <li>
                                    <button type="button"
                                        class="dropdown-item d-flex align-items-center custom__dropdown__item"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteAnnouncement{{ $announcement->id }}">
                                        <i class="fa-solid fa-trash-can me-3"></i>
                                        Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="announcement__description">{!! $announcement->description !!}</div>
                    <div class="d-flex align-items-center justify-content-between">
                        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                            aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-building-circle-check"></i>
                                    {{ $announcement->department->name }}
                                </li>
                            </ol>
                        </nav>
                        <span class="badge text-bg-warning">{{ $announcement->getImportanceStatus() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
