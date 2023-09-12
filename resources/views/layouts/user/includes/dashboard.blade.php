@extends('layouts.user.base', ['title' => 'Dashboard'])

@section('main-content')
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card dashboard__card"
            onclick="window.location.href='{{ route('user.tickets.open_tickets') }}'">
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
    <div class="col-xl-3 col-md-6" onclick="window.location.href='{{ route('user.tickets.viewed_tickets') }}'">
        <div class="card custom__card dashboard__card">
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
            onclick="window.location.href='{{ route('user.tickets.approved_tickets') }}'">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-thumbs-up"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">{{ $approvedTickets->count() }}</p>
                    <h6 class="card__title">
                        Approved
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card dashboard__card"
            onclick="window.location.href='{{ route('user.tickets.disapproved_tickets') }}'">
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
            onclick="window.location.href='{{ route('user.tickets.claimed_tickets') }}'">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-thumbs-down"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">{{ $claimedTickets->count() }}</p>
                    <h6 class="card__title">
                        Claimed
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card dashboard__card"
            onclick="window.location.href='{{ route('user.tickets.on_process_tickets') }}'">
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
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card dashboard__card">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-envelope-open"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">--</p>
                    <h6 class="card__title">
                        Reopened
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card custom__card dashboard__card"
            onclick="window.location.href='{{ route('user.tickets.closed_tickets') }}'">
            <div class="d-flex align-items-center justify-content-start gap-4 card__content">
                <div class="d-flex align-items-center justify-content-center icon__container">
                    <i class="fa-solid fa-envelope-circle-check"></i>
                </div>
                <div class="d-flex flex-column">
                    <p class="mb-0 ticket__count">{{ $closedTickets->count() }}</p>
                    <h6 class="card__title">
                        Closed
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection