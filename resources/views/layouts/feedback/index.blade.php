<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select/virtual-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select/custom-virtual-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/feedback.css') }}">
    <title>E-JO - {{ $title ?? 'Feedback' }}</title>
</head>

<body class="{{ Route::is('feedback.index') ? 'bg-white' : '' }}">
    @include('layouts.feedback.includes.nav__and__banner')
    @include('layouts.feedback.includes.tab')
    <div class="container feedback__container">
        @section('feedback-content')
        @include('layouts.feedback.includes.welcome_message')
        @show
    </div>
    {{-- @include('layouts.feedback.includes.button_create_feedback') --}}
    @include('layouts.staff.includes.toaster-message')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/toaster-message.js') }}"></script>
    <script src="{{ asset('js/select/virtual-select.min.js') }}"></script>
    <script src="{{ asset('js/init/virtual-select-init.js') }}"></script>

    @stack('toastr-message-js')
    @if ($errors->storeFeedback->any())
    <script>
        $(function () {
            $('#modalFeedback').modal('show');
        });

    </script>
    @endif
</body>

</html>