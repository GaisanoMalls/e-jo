<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>
        {{ $title ?? '' }} Sign in | E-JO System
    </title>
    @livewireStyles
</head>

<body>
    <section>
        <div class="container-fluid">
            <div class="row">
                @if (Route::is('forgot_password.index'))
                <div class="col-xl-6 b-center bg-size"
                    style="background-image: url({{ asset('images/auth/forgot-password.jpg') }}); background-size: cover; background-repeat: no-repeat; display: block;">
                    <img class="bg-img-cover bg-center" src="{{ asset('images/auth/forgot-password.jpg') }}"
                        alt="looginpage" style="display: none;">
                </div>
                @else
                <div class="col-xl-6 b-center bg-size"
                    style="background-image: url({{ asset('images/auth/auth-pic.jpg') }}); background-size: cover; background-repeat: no-repeat; display: block;">
                    <img class="bg-img-cover bg-center" src="{{ asset('images/auth/auth-pic.jpg') }}" alt="looginpage"
                        style="display: none;">
                </div>
                @endif
                <div class="col-xl-6 p-0">
                    <div class="login__card">
                        <div class="login__form__container my-3 position-relative d-flex flex-column">
                            @include('layouts.auth.includes.app_name')
                            @yield('form-title')
                            @section('form-section')
                            @livewire('auth-user')
                            @show
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @livewireScripts
</body>

</html>