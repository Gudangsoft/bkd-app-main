<html lang="en" data-footer="true">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>@yield('title') | {{ site()->title }}</title>
    <meta name="description"
        content="{{ site()->description }}" />

    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets') }}/font/CS-Interface/style.css" />
    <link rel="stylesheet" href="{{ asset('assets/css') }}/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css') }}/vendor/OverlayScrollbars.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css') }}/vendor/glide.core.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css') }}/vendor/introjs.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css') }}/vendor/select2.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css') }}/vendor/select2-bootstrap4.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css') }}/vendor/plyr.css" />
    <link rel="stylesheet" href="{{ asset('assets/css') }}/styles.css" />
    @stack('styles')

    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <link rel="stylesheet" href="{{ asset('assets/css') }}/main.css" />
    <link rel="stylesheet" href="{{ asset('assets/css') }}/theme-modern.css" />
    <script src="{{ asset('assets/js') }}/base/loader.js"></script>
</head>

<body>
    <div id="root">
        <div id="nav" class="nav-container d-flex">
            @include('admin.layouts.navbar')
            <div class="nav-shadow"></div>
        </div>

        <main>
            @yield('content')
        </main>

        @include('admin.layouts.footer')
    </div>

    <!-- Search Modal Start -->
    <div class="modal fade modal-under-nav modal-search modal-close-out" id="searchPagesModal" tabindex="-1"
        role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 p-0">
                    <button type="button" class="btn-close btn btn-icon btn-icon-only btn-foreground"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ps-5 pe-5 pb-0 border-0">
                    <input id="searchPagesInput"
                        class="form-control form-control-xl borderless ps-0 pe-0 mb-1 auto-complete" type="text"
                        autocomplete="off" />
                </div>
                <div class="modal-footer border-top justify-content-start ps-5 pe-5 pb-3 pt-3 border-0">
                    <span class="text-alternate d-inline-block m-0 me-3">
                        <i data-acorn-icon="arrow-bottom" data-acorn-size="15"
                            class="text-alternate align-middle me-1"></i>
                        <span class="align-middle text-medium">Navigate</span>
                    </span>
                    <span class="text-alternate d-inline-block m-0 me-3">
                        <i data-acorn-icon="arrow-bottom-left" data-acorn-size="15"
                            class="text-alternate align-middle me-1"></i>
                        <span class="align-middle text-medium">Select</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- Search Modal End -->

    <!-- Vendor Scripts Start -->
    <script src="{{ asset('assets/js') }}/vendor/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('assets/js') }}/vendor/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js') }}/vendor/OverlayScrollbars.min.js"></script>
    <script src="{{ asset('assets/js') }}/vendor/autoComplete.min.js"></script>
    <script src="{{ asset('assets/js') }}/vendor/clamp.min.js"></script>

    <script src="{{ asset('assets/icon') }}/acorn-icons.js"></script>
    <script src="{{ asset('assets/icon') }}/acorn-icons-interface.js"></script>
        {{-- <script src="{{ asset('assets/js') }}/vendor/Chart.bundle.min.js"></script>
        <script src="{{ asset('assets/js') }}/vendor/chartjs-plugin-datalabels.js"></script>
        <script src="{{ asset('assets/js') }}/vendor/chartjs-plugin-rounded-bar.min.js"></script> --}}
    <script src="{{ asset('assets/js') }}/vendor/glide.min.js"></script>
    <script src="{{ asset('assets/js') }}/vendor/intro.min.js"></script>
    <script src="{{ asset('assets/js') }}/vendor/select2.full.min.js"></script>

    <script src="{{ asset('assets/js') }}/base/helpers.js"></script>
    <script src="{{ asset('assets/js') }}/base/globals.js"></script>
    <script src="{{ asset('assets/js') }}/base/nav.js"></script>
    <script src="{{ asset('assets/js') }}/base/search.js"></script>
    <script src="{{ asset('assets/js') }}/base/settings.js"></script>

    <script src="{{ asset('assets/js') }}/cs/glide.custom.js"></script>
    <script src="{{ asset('assets/js') }}/cs/charts.extend.js"></script>
    <script src="{{ asset('assets/js') }}/pages/dashboard.default.js"></script>
    <script src="{{ asset('assets/js') }}/common.js"></script>
    <script src="{{ asset('assets/js') }}/scripts.js"></script>
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    @stack('scripts')
    @include('sweetalert::alert')

    @livewireScripts
</body>

</html>
