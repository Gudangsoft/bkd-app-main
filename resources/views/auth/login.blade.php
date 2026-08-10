<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Login | {{ config('app.name') }}</title>
    <meta name="description" content="Login Page" />
    <!-- Favicon Tags Start -->
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="img/favicon/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/favicon/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/favicon/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/favicon/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="img/favicon/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="img/favicon/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="img/favicon/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="img/favicon/apple-touch-icon-152x152.png" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-196x196.png" sizes="196x196" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-128.png" sizes="128x128" />
    <meta name="application-name" content="&nbsp;" />
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="img/favicon/mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="img/favicon/mstile-70x70.png" />
    <meta name="msapplication-square150x150logo" content="img/favicon/mstile-150x150.png" />
    <meta name="msapplication-wide310x150logo" content="img/favicon/mstile-310x150.png" />
    <meta name="msapplication-square310x310logo" content="img/favicon/mstile-310x310.png" />
    <!-- Favicon Tags End -->
    <!-- Font Tags Start -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets') }}/font/CS-Interface/style.css" />
    <!-- Font Tags End -->
    <!-- Vendor Styles Start -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/css/vendor/OverlayScrollbars.min.css" />

    <!-- Vendor Styles End -->
    <!-- Template Base Styles Start -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/styles.css" />
    <!-- Template Base Styles End -->

    <link rel="stylesheet" href="{{ asset('assets') }}/css/main.css" />
    <script src="{{ asset('assets') }}/js/base/loader.js"></script>
</head>

@php
    $loginBackgroundUrl = optional(\App\Models\Setting::first())->loginBackgroundUrl
        ?? asset('assets/img/background/background-blue.webp');
@endphp
<body class="h-100">
    <div id="root" class="h-100">
        <!-- Background Start -->
        <div class="fixed-background" style="background-image: url('{{ $loginBackgroundUrl }}')"></div>
        <!-- Background End -->

        <div class="container-fluid p-0 h-100 position-relative">
            <div class="row g-0 h-100">
                <!-- Left Side Start -->
                <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">
                    <div class="min-h-100 d-flex align-items-center">
                        <div class="w-100 w-lg-75 w-xxl-50">
                        </div>
                    </div>
                </div>
                <!-- Left Side End -->

                <!-- Right Side Start -->
                <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
                    <div
                        class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
                        <div class="sw-lg-50 px-5">
                            <div class="sh-11">
                                <a href="#">
                                    <h2 class="text-primary">Login {{ config('app.name') }}</h2>
                                </a>
                            </div>
                            <div class="mb-5">
                                <p class="h6">Silakan gunakan akun anda untuk login.</p>
                                <p class="h6">
                                    Jika anda pengguna baru, silakan
                                    <a href="{{ route('register') }}">register</a>
                                    .
                                </p>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3 filled form-group tooltip-end-top">
                                        <i data-acorn-icon="email"></i>
                                        <input class="form-control" placeholder="Email" name="email"
                                            value="{{ old('email') }}" required />
                                    </div>
                                    <div class="mb-3 filled form-group tooltip-end-top">
                                        <i data-acorn-icon="lock-off"></i>
                                        <input class="form-control pe-7" name="password" type="password"
                                            placeholder="******" value="{{ old('password') }}" required />
                                        {{-- <a class="text-small position-absolute t-3 e-3" href="Pages.Authentication.ForgotPassword.html">Forgot?</a> --}}
                                    </div>
                                    <div class="mb-3 filled form-group tooltip-end-top">
                                        @if (Route::has('password.request'))
                                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                href="{{ route('password.request') }}">
                                                {{ __('Forgot your password?') }}
                                            </a>
                                        @endif
                                    </div>
                                    <input type="submit" class="btn btn-lg btn-primary" value="Login">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Scripts Start -->
    <script src="{{ asset('assets') }}/js/vendor/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('assets') }}/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets') }}/js/vendor/OverlayScrollbars.min.js"></script>
    <script src="{{ asset('assets') }}/js/vendor/autoComplete.min.js"></script>
    <script src="{{ asset('assets') }}/js/vendor/clamp.min.js"></script>

    <script src="{{ asset('assets') }}/icon/acorn-icons.js"></script>
    <script src="{{ asset('assets') }}/icon/acorn-icons-interface.js"></script>

    <!-- Vendor Scripts End -->

    <!-- Template Base Scripts Start -->
    <script src="{{ asset('assets') }}/js/base/helpers.js"></script>
    <script src="{{ asset('assets') }}/js/base/globals.js"></script>
    <script src="{{ asset('assets') }}/js/base/nav.js"></script>
    <script src="{{ asset('assets') }}/js/base/search.js"></script>
    <script src="{{ asset('assets') }}/js/base/settings.js"></script>
    <!-- Template Base Scripts End -->
    <!-- Page Specific Scripts Start -->

    <script src="{{ asset('assets') }}/js/pages/auth.login.js"></script>

    <script src="{{ asset('assets') }}/js/common.js"></script>
    <script src="{{ asset('assets') }}/js/scripts.js"></script>
    <!-- Page Specific Scripts End -->
</body>

</html>

{{-- <x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout> --}}
