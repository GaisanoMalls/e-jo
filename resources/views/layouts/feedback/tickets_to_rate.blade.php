@extends('layouts.feedback.index', ['title' => 'Tickets to rate'])

@section('feedback-content')
<div class="row my-5 gap-4">
    @if (!$closedTickets->isEmpty())
    @foreach ($closedTickets as $ticket)
    @include('layouts.feedback.includes.modal.create_feedback_modal_form')
    <div class="card border-0 feedback__card">
        <div class="d-flex flex-wrap align-items-center justify-content-between card__header">
            <p class="mb-1 ticket__container">
                <span class="lbl__ticket__num">Ticket #</span>
                <span class="ticket__number">{{ $ticket->ticket_number }}</span>
            </p>
            <button class="btn btn-sm d-flex align-items-center gap-1 btn__give__rate" data-bs-toggle="modal"
                data-bs-target="#ticketFeedbackModal{{ $ticket->id }}">
                <i class="fa-solid fa-star"></i>
                Give Feedback
            </button>
        </div>
        <div class="card__body bg-white p-0">
            <div class="d-flex flex-column gap-4">
                <div class="mb-0">
                    <h6 class="mb-1 ticket__title">{{ $ticket->subject }}</h6>
                    <div class="ticket__description">
                        {!! $ticket->description !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between feedback__footer">
            <div class="d-flex align-items-center gap-1 replies__count">
                <i class="fa-solid fa-comment-dots"></i>
                {{ $ticket->replies->count() }}
            </div>
            <span class="date">Closed last Jun 3, 2023</span>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection