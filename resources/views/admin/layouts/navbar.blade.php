<div class="nav-content d-flex">
    <div class="logo position-relative">
        <a href="/">
            @if (site()->logo)
                <img src="{{ asset('storage/logo') . '/' . site()->logo }}" alt="{{ site()->title }}"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display=''" />
                <span class="text-dark fw-bold" style="display:none;">{{ site()->title }}</span>
            @else
                <span class="text-dark fw-bold">{{ site()->title }}</span>
            @endif
        </a>
    </div>

    <div class="user-container d-flex">
        @if (isset(auth()->user()->id))
            <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img class="profile" alt="profile"
                    src="{{ auth()->user()->imagePath ? auth()->user()->imagePath : asset('assets/img/profile/profile-default.png') }}" />
                <div class="name">{{ auth()->user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-end user-menu wide">
                <div class="row ms-0 me-0">
                    <div class="col-12 ps-1 mb-2">
                        <div class="text-extra-small text-primary">ACCOUNT</div>
                    </div>
                </div>
                <div class="row mb-1 ms-0 me-0">
                    <div class="col-12 p-1">
                        <div class="separator-light"></div>
                    </div>
                    <div class="col-6 ps-1 pe-1">
                        @isset(auth()->user()->id)
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route('users.profile', auth()->user()->id) }}">
                                        <i data-acorn-icon="user" class="me-2" data-acorn-size="17"></i>
                                        <span class="align-middle">Profile</span>
                                    </a>
                                </li>
                            </ul>
                        @endisset
                    </div>
                    <div class="col-6 pe-1 ps-1">
                        <ul class="list-unstyled">

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                this.closest('form').submit(); "
                                        role="button">
                                        <i data-acorn-icon="logout" class="me-2" data-acorn-size="17"></i>
                                        <span class="align-middle">Logout</span>
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="d-flex position-relative text-white" style="align-items: center;">
                <i data-acorn-icon="login" class="me-2" data-acorn-size="17"></i>
                <div class="name fw-bold">LOGIN</div>
            </a>
        @endif

    </div>
    <!-- User Menu End -->

    <!-- Icons Menu Start -->
    <ul class="list-unstyled list-inline text-center menu-icons">
        <li class="list-inline-item">
            <a href="#" id="pinButton" class="pin-button">
                <i data-acorn-icon="lock-on" class="unpin" data-acorn-size="18"></i>
                <i data-acorn-icon="lock-off" class="pin" data-acorn-size="18"></i>
            </a>
        </li>
    </ul>
    <!-- Icons Menu End -->

    <!-- Menu Start -->
    @include('admin.layouts.menu')
    <!-- Menu End -->

    <!-- Mobile Buttons Start -->
    <div class="mobile-buttons-container">
        <!-- Scrollspy Mobile Button Start -->
        <a href="#" id="scrollSpyButton" class="spy-button" data-bs-toggle="dropdown">
            <i data-acorn-icon="menu-dropdown"></i>
        </a>
        <!-- Scrollspy Mobile Button End -->

        <!-- Scrollspy Mobile Dropdown Start -->
        <div class="dropdown-menu dropdown-menu-end" id="scrollSpyDropdown"></div>
        <!-- Scrollspy Mobile Dropdown End -->

        <!-- Menu Button Start -->
        <a href="#" id="mobileMenuButton" class="menu-button">
            <i data-acorn-icon="menu"></i>
        </a>
        <!-- Menu Button End -->
    </div>
    <!-- Mobile Buttons End -->
</div>
