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
    <link rel="stylesheet" href="{{ asset('css/roles/approver.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigate-indicator.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <title>{{ $title ?? '' }}</title>
    @livewireStyles
</head>

<body>

    @php
        use App\Http\Traits\Utils;
    @endphp

    @include('layouts.staff.approver.includes.navbar')
    @livewire('approver.notification.notification-canvas')
    @include('layouts.staff.approver.includes.modal.confirm_logout')
    <div class="container mb-5 approver__section">
        @livewire('offline')
        @if (!Utils::costingApprover2Only())
            @if (Route::is('approver.tickets.*'))
                @livewire('approver.ticket-tab')
            @endif
        @endif
        @yield('main-content')
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="{{ asset('js/select/virtual-select.min.js') }}"></script>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/roles/staff/approver.js') }}"></script>
    <script src="{{ asset('js/init/virtual-select-init.js') }}"></script>
    <script src="{{ asset('js/alpine.js') }}"></script>

    @stack('livewire-modal')
    @stack('livewire-textarea')
    @stack('modal-with-error')
</body>

</html>
