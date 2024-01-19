<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/tooltip.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select/virtual-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select/custom-virtual-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/roles/staff.css') }}">
    <title>{{ $title }}</title>
    @livewireStyles
</head>

<body>
    <div class="page__wrapper compact__wrapper">
        @include('layouts.staff.includes.header')
        @livewire('staff.notification.notification-canvas')
        <div class="page__body__wrapper">
            @include('layouts.staff.includes.sidebar')
            <div class="page__body">
                @livewire('offline')
                <div class="container-fluid">
                    <div class="page__header">
                        <div class="row">
                            @yield('page-header')
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    @yield('main-content')
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="{{ asset('js/select/virtual-select.min.js') }}"></script>
    <script src="{{ asset('js/init/virtual-select-init.js') }}"></script>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/tooltip.min.js') }}"></script>
    <script src="{{ asset('js/roles/staff/staff.js') }}"></script>
    <script src="{{ asset('js/ticket-jquery.js') }}"></script>
    <script src="{{ asset('js/init/tinymce-init.js') }}"></script>
    <script src="{{ asset('js/roles/staff/dependent-dropdown.js') }}"></script>
    <script src="{{ asset('js/alpine.js') }}"></script>
    @stack('livewire-select')
    @stack('livewire-modal')
    @stack('livewire-textarea')
    @stack('modal-with-error')
    @stack('extra')
</body>

</html>
