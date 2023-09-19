<div wire:poll.visible.7s>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card custom__card dashboard__card"
                onclick="window.location.href='{{ route('approver.tickets.open') }}'">
                <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                    <div class="d-flex align-items-center justify-content-center icon__container">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="mb-0 ticket__count">{{ $openTickets->count() }}</p>
                        <h6 class="card__title">
                            Open
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom__card dashboard__card"
                onclick="window.location.href='{{ route('approver.tickets.viewed') }}'">
                <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                    <div class="d-flex align-items-center justify-content-center icon__container">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="mb-0 ticket__count">{{ $viewedTickets->count() }}</p>
                        <h6 class="card__title">
                            Viewed
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom__card dashboard__card"
                onclick="window.location.href='{{ route('approver.tickets.approved') }}'">
                <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                    <div class="d-flex align-items-center justify-content-center icon__container">
                        <i class="fa-solid fa-thumbs-up"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="mb-0 ticket__count">{{ $viewedTickets->count() }}</p>
                        <h6 class="card__title">
                            Approved
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom__card dashboard__card"
                onclick="window.location.href='{{ route('approver.tickets.disapproved') }}'">
                <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                    <div class="d-flex align-items-center justify-content-center icon__container">
                        <i class="fa-solid fa-thumbs-down"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="mb-0 ticket__count">{{ $disapprovedTickets->count() }}</p>
                        <h6 class="card__title">
                            Disapproved
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom__card dashboard__card"
                onclick="window.location.href='{{ route('approver.tickets.on_process') }}'">
                <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                    <div class="d-flex align-items-center justify-content-center icon__container">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="mb-0 ticket__count">{{ $onProcessTickets->count() }}</p>
                        <h6 class="card__title">
                            On Process
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>