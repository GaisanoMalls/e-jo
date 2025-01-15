<div class="row my-4">
    @foreach ($ticketStatuses as $status)
        @php
            $percentage = $totalTickets > 0 ? ($status['count'] / $totalTickets) * 100 : 0;
        @endphp
        <a href="{{ route($status['routeName']) }}" class="col-xxl-3 col-lg-4 col-md-6 col-sm-6 col-12 status__card__link">
            <div class="card dashboard__card__by__status card__rounded__and__no__border">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column w-50 gap-2">
                        <h5 class="card__label__name mb-0">{{ $status['name'] }}</h5>
                        <div class="d-flex align-items-center progress__bar__container gap-2">
                            <p class="progress__bar__ticket__count mb-0" style="color: {{ $status['color'] }}">{{ $status['count'] }}</p>
                            <div class="progress custom__progress">
                                <div class="progress-bar custom__progress__bar" role="progressbar" style="width: {{ $percentage }}%; background-color: {{ $status['color'] }};" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center dashboad__card__icon__container">
                        <i class="fa-solid {{ $status['icon'] }}" style="color: {{ $status['color'] }};"></i>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>
