@extends('layouts.user.base', ['title' => 'Dashboard'])

@section('main-content')
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">{{ $openTickets->count() }}</p>
                    <h6 class="card__title">
                        Open Tickets
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-gears"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">{{ $onProcessTickets->count() }}</p>
                    <h6 class="card__title">
                        On Process Tickets
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">{{ $viewedTickets->count() }}</p>
                    <h6 class="card__title">
                        Viewed Tickets
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-thumbs-up"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">{{ $approvedTickets->count() }}</p>
                    <h6 class="card__title">
                        Approved Tickets
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-thumbs-down"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">{{ $disapprovedTickets->count() }}</p>
                    <h6 class="card__title">
                        Disapproved Tickets
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-envelope-open"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">--</p>
                    <h6 class="card__title">
                        Reopened Tickets
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-envelope-circle-check"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">{{ $closedTickets->count() }}</p>
                    <h6 class="card__title">
                        Closed Tickets
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection