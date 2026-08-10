<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ site()->title }}</title>
    <meta name="description" content="{{ site()->description }}" />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')

    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="h-full min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
    <x-banner />

    <div class="flex min-h-screen flex-col">
        @include('admin.layouts.partials.topbar')

        <main class="mx-auto w-full flex-1 px-4 py-8 sm:px-6 lg:px-8">
            @yield('content')
        </main>

        @include('admin.layouts.partials.footer')
    </div>

    @stack('modals')

    @stack('scripts')
    @include('sweetalert::alert')

    @livewireScripts
</body>

</html>
