<div class="card announcement__card">
    <div class="announcement__card__header d-flex flex-wrap align-items-center justify-content-between">
        <div class="col-12">
            <div class="row">
                <div class="col-xxl-5 col-lg-5 col-md-7 col-10">
                    <div class="d-flex align-items-center position-relative">
                        <input type="text" class="form-control search__field" placeholder="Type here to search...">
                        <i class="fa-solid fa-magnifying-glass announcement__search__icon"></i>
                    </div>
                </div>
                <div class="col-xxl-7 col-lg-7 col-md-5 col-2 d-flex justify-content-end align-items-center">
                    <button type="button"
                        class="btn d-flex align-items-center justify-content-center gap-1 btn__filter__announcement">
                        <i class="bi bi-funnel-fill"></i>
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="announcement__card__list d-flex flex-column">
        <div class="w-100 d-flex flex-column gap-4">
            @include('layouts.staff.announcement.includes.day.today')
            @include('layouts.staff.announcement.includes.day.yesterday')
            @include('layouts.staff.announcement.includes.day.recent')
        </div>
    </div>
</div>