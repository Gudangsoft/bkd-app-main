@php
    $assignmentLetterCount = auth()->check() && auth()->user()->hasRole('asesor')
        ? \App\Models\AssignmentLetter::where('assessor_id', auth()->user()->id)->count()
        : 0;
@endphp

<header x-data="{ mobileOpen: false }" class="sticky top-0 z-30 border-b border-gray-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-screen-2xl items-center gap-6 px-4 sm:px-6 lg:px-8">
        <a href="/" class="flex flex-shrink-0 items-center gap-2">
            @if (site()->logo)
                <img
                    src="{{ asset('storage/logo') . '/' . site()->logo }}"
                    alt="{{ site()->title }}"
                    class="h-8 w-auto"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block'"
                />
                <span class="hidden text-base font-semibold text-gray-900">{{ site()->title }}</span>
            @else
                <span class="text-base font-semibold text-gray-900">{{ site()->title }}</span>
            @endif
        </a>

        <nav class="hidden min-w-0 flex-1 items-center gap-1 lg:flex">
            @include('admin.layouts.partials.nav-items', ['mobile' => false, 'assignmentLetterCount' => $assignmentLetterCount])
        </nav>

        <div class="flex flex-shrink-0 items-center gap-2">
            @auth
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" @click="open = !open" class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-gray-100">
                        <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->imagePath ? auth()->user()->imagePath : asset('assets/img/profile/profile-default.png') }}" alt="{{ auth()->user()->name }}" />
                        <span class="hidden text-sm font-medium text-gray-700 sm:block">{{ auth()->user()->name }}</span>
                        <x-admin.icon name="chevron-down" class="hidden h-4 w-4 text-gray-400 sm:block" />
                    </button>

                    <div x-show="open" x-cloak x-transition class="absolute right-0 z-20 mt-2 w-48 rounded-xl border border-gray-100 bg-white p-1 shadow-lg">
                        <a href="{{ route('users.profile', auth()->user()->id) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">
                            <x-admin.icon name="user" class="h-4 w-4" /> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" role="button" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">
                                <x-admin.icon name="logout" class="h-4 w-4" /> Logout
                            </a>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 rounded-lg bg-accent-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-accent-700">
                    <x-admin.icon name="user" class="h-4 w-4" /> Login
                </a>
            @endauth

            <button type="button" @click="mobileOpen = !mobileOpen" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden">
                <x-admin.icon name="menu" x-show="!mobileOpen" class="h-6 w-6" />
                <x-admin.icon name="x-mark" x-show="mobileOpen" x-cloak class="h-6 w-6" />
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" x-cloak x-transition class="border-t border-gray-200 px-4 py-3 lg:hidden">
        <nav class="flex flex-col gap-1">
            @include('admin.layouts.partials.nav-items', ['mobile' => true, 'assignmentLetterCount' => $assignmentLetterCount])
        </nav>
    </div>
</header>
