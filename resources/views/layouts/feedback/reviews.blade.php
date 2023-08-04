@extends('layouts.feedback.index', ['title' => 'My Reviews'])

@section('feedback-content')
<div class="row my-5 gap-4">
    @foreach ($reviews as $review)
    <div class="card border-0 feedback__card">
        <div class="d-flex flex-wrap align-items-center justify-content-between card__header">
            <div class="d-flex align-items-center feedback__user">
                <img src="https://samuelsabellano.pythonanywhere.com/media/profile/1_Sabellano_Samuel_Jr_C__DSC9469.JPG"
                    class="user__picture" alt="">
                <span class="user__name">
                    {{ auth()->user()->profile->first_name }}
                    @if (auth()->user()->id === $review->user_id)
                    <small style="font-size: 11px; color: #808080;">
                        <i class="fa-solid fa-check"></i>
                    </small>
                    @endif
                </span>
            </div>
            <div class="d-flex align-items-center gap-1 ratings">
                @for ($i = 1; $i <= 5; $i++) @if ($i <=$review->rating)
                    <i class="fa-solid fa-star filled"></i>
                    @else
                    <i class="fa-solid fa-star"></i>
                    @endif
                    @endfor
            </div>
        </div>
        <div class="card__body">
            <div class="d-flex flex-column gap-4">
                @if ($review->description)
                <div class="mb-0">
                    <h6 class="mb-1 body__section__title">Feedback</h6>
                    <div class="mb-0 long__desc">
                        {{ $review->description }}
                    </div>
                </div>
                @endif
                @if ($review->suggestion)
                <div class="mb-0">
                    <h6 class="mb-1 body__section__title">Suggestions/recommendations</h6>
                    <div class="mb-0 long__desc">
                        {{ $review->suggestion }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between feedback__footer">
            <a href="" class="ticket">
                <span>Ticket #</span>
                <span class="number">K472-05</span>
            </a>
            <span class="date">May 16, 2023</span>
        </div>
    </div>
    @endforeach
</div>
@endsection