@extends('layouts.staff.base', ['title' => 'Feedbacks'])

@section('page-header')
    <div class="justify-content-between d-flex flex-wrap ticket__content__top">
        <div class="col-lg-7 col-md-5">
            <h3 class="page__header__title">Feedbacks</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Feedback</li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </div>
    </div>
@endsection

@section('main-content')
    <div class="row">
        <div class="ticket__section feedback">
            <div class="col-12">
                <div class="card d-flex flex-column tickets__card p-0">
                    <div class="tickets__card__header pb-0 pt-4 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex flex-column me-3">
                                <h6 class="card__title">Ticket Feedbacks</h6>
                                <p class="card__description">
                                    Review requester's feedbacks and suggestions
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tickets__table__card">
                        <div class="table-responsive custom__table">
                            @if ($feedbacks->isNotEmpty())
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                Ticket Number
                                            </th>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                Requester
                                            </th>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                Feedback
                                            </th>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                Agent
                                            </th>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px">
                                                Date Created
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($feedbacks as $feedback)
                                            <tr class="ticket__tr align-item" onclick="window.location='{{ route('staff.ticket.view_ticket', $feedback->ticket->id) }}'">
                                                <td style="vertical-align: top;">
                                                    <div class="d-flex text-start gap-3 td__content">
                                                        <span>{{ $feedback->ticket->ticket_number }}</span>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <div class="d-flex text-start gap-3 td__content">
                                                        <div class="d-flex align-items-center user__account__media">
                                                            @if ($feedback->ticket->user?->profile)
                                                                <img src="{{ Storage::url($feedback->ticket->user?->profile->picture) }}" class="image-fluid ticket__details__user__picture" alt="">
                                                            @else
                                                                <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center text-white" style="background-color: #24695C;">
                                                                    {{ $feedback->ticket->user?->profile->getNameInitial() }}
                                                                </div>
                                                            @endif
                                                            <div class="d-flex flex-column">
                                                                <small class="fw-semibold ticket__details__user__fullname" style="font-size: 0.8rem;">
                                                                    {{ $feedback->ticket->user?->profile->getFullName }}
                                                                </small>
                                                                <small class="ticket__details__user__department">
                                                                    {{ $feedback->ticket->user?->getBUDepartments() }} -
                                                                    {{ $feedback->ticket->user?->getBranches() }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-start td__content" style="min-width: 18rem !important; max-width: 30rem; width: auto;">
                                                        <div class="d-flex flex-column gap-1">
                                                            <div class="d-flex gap-1 stars__container">
                                                                @for ($rating = 1; $rating <= 5; $rating++)
                                                                    @if ($rating <= $feedback->rating)
                                                                        <i class="fa-solid fa-star filled"></i>
                                                                    @else
                                                                        <i class="fa-solid fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                            <small class="fw-semibold" style="font-size: 0.8rem; color: #1f2937;">
                                                                @if ($feedback->rating == 1)
                                                                    Terrible
                                                                @endif
                                                                @if ($feedback->rating == 2)
                                                                    Bad
                                                                @endif
                                                                @if ($feedback->rating == 3)
                                                                    Good
                                                                @endif
                                                                @if ($feedback->rating == 4)
                                                                    Very Good
                                                                @endif
                                                                @if ($feedback->rating == 5)
                                                                    Excellent
                                                                @endif
                                                            </small>
                                                            <small class="text-wrap" style="font-size: 0.84rem;">
                                                                {{ $feedback->description }}
                                                            </small>

                                                            @if ($feedback->suggestion)
                                                                <div class="d-flex flex-column mt-2 gap-1">
                                                                    <span class="fw-normal" style="font-size: 0.8rem; color: #8a92a1;">Suggestion</span>
                                                                    <small class="text-wrap" style="font-size: 0.84rem;">
                                                                        {{ $feedback->suggestion }}
                                                                    </small>
                                                                </div>
                                                            @endif

                                                            @if ($feedback->had_issues_encountered == 'Yes')
                                                                <div class="d-flex align-items-center rounded-2 justify-content-center gap-2" style="background-color: #e2e2e1; width: 11.3rem; padding: 2px 8px;">
                                                                    <i class="bi bi-info-circle"></i>
                                                                    <small style="color: #585858;">
                                                                        Had issues encountered
                                                                    </small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <div class="d-flex text-start td__content">
                                                        @if ($feedback->ticket->agent)
                                                            <div class="d-flex align-items-center user__account__media">
                                                                @if (!$feedback->ticket->agent->profile)
                                                                    <img src="{{ Storage::url($feedback->ticket->agent->profile->picture) }}" class="image-fluid ticket__details__user__picture" alt="">
                                                                @else
                                                                    <div class="user__name__initial d-flex align-items-center p-2 me-2 justify-content-center text-white" style="background-color: #196837;">
                                                                        {{ $feedback->ticket->agent->profile->getNameInitial() }}
                                                                    </div>
                                                                @endif
                                                                <div class="d-flex flex-column">
                                                                    <small class="fw-semibold ticket__details__user__fullname" style="font-size: 0.8rem;">
                                                                        {{ $feedback->ticket->agent->profile->getFullName }}
                                                                    </small>
                                                                    <small class="ticket__details__user__department">
                                                                        {{ $feedback->ticket->agent->getBUDepartments() }}
                                                                        -
                                                                        {{ $feedback->ticket->agent->getBranches() }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        @else
                                                            ----
                                                        @endif
                                                    </div>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <div class="d-flex text-start td__content">
                                                        <span>
                                                            {{ \Carbon\Carbon::parse($feedback->created_at)->format('M d, Y | g:i A') }}
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                                    <small style="font-size: 14px;">Empty feedbacks</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
