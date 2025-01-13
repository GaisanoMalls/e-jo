<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/icons/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select/virtual-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select/custom-virtual-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/roles/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigate-indicator.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <title>{{ $title ?? 'Dashboard' }}</title>
    @livewireStyles
</head>

<body>
    @include('layouts.user.includes.navbar')
    @include('layouts.user.account.includes.confirm_logout')
    @livewire('requester.ticket.create-ticket')
    @livewire('requester.notification.notification-canvas')
    <div class="container-fluid p-lg-4 p-sm-2 requester__section">
        @livewire('offline')
        @if (Route::is('user.tickets.*'))
            @livewire('requester.ticket-tab')
            <div class="row mx-0 ticket__content header user">
                <div class="d-flex flex-wrap px-0 align-items-center justify-content-between">
                    @section('ticket-list-header')
                        <h5 class="mb-0 content__title">All Tickets</h5>
                    @show
                </div>
            </div>
        @endif
        @yield('main-content')
    </div>

    @livewireScripts
    @yield('action-js')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="{{ asset('js/select/virtual-select.min.js') }}"></script>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/init/tinymce-init.js') }}"></script>
    <script src="{{ asset('js/init/virtual-select-init.js') }}"></script>
    <script src="{{ asset('js/alpine.js') }}"></script>
    <script src="{{ asset('js/lightbox.js') }}"></script>
    @stack('livewire-textarea')
    @stack('livewire-textarea-disapproval')
    @stack('livewire-modal')
    @stack('livewire-select')
    @stack('extra')
    <script>
        lightbox.option({
            'resizeDuration': 50,
            'wrapAround': true,
            'fitImagesInViewport': true,
            'disableScrolling': true,
            'alwaysShowNavOnTouchDevices': true
        })
    </script>
</body>

</html>
