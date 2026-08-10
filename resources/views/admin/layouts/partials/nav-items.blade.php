@php
    $mobile = $mobile ?? false;
    $assignmentLetterCount = $assignmentLetterCount ?? 0;
    $base = $mobile
        ? 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium'
        : 'flex flex-shrink-0 items-center gap-2 whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium';
    $inactive = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
    $active = 'bg-accent-50 text-accent-700';
    $groupWrap = $mobile ? '' : 'relative flex-shrink-0';
    $panelClass = $mobile
        ? 'ml-8 mt-1 flex flex-col gap-1'
        : 'absolute left-0 z-20 mt-1 w-56 rounded-xl border border-gray-100 bg-white p-1 shadow-lg';
@endphp

<a href="/dashboard" class="{{ $base }} {{ request()->is('dashboard') ? $active : $inactive }}">
    <x-admin.icon name="home" class="h-5 w-5" />
    <span>Dashboards</span>
</a>

@role('dosen|operator|finance|admin|guest')
    <a href="{{ route('payments.index') }}" class="{{ $base }} {{ request()->routeIs('payments.*') ? $active : $inactive }}">
        <x-admin.icon name="wallet" class="h-5 w-5" />
        <span>{{ __('dashboard.payment.titles') }}</span>
    </a>
@endrole

@role('asesor')
    <div x-data="{ open: false }" class="{{ $groupWrap }}">
        <button type="button" @click="open = !open" class="{{ $base }} w-full justify-between {{ request()->routeIs('data-dosen-assessor') || request()->routeIs('asset-keuangan') ? $active : $inactive }}">
            <span class="flex items-center gap-2"><x-admin.icon name="database" class="h-5 w-5" /><span>Master Data</span></span>
            <x-admin.icon name="chevron-down" class="h-4 w-4 transition-transform" x-bind:class="{ 'rotate-180': open }" />
        </button>
        <div x-show="open" x-cloak x-transition class="{{ $panelClass }}">
            <a href="{{ route('data-dosen-assessor') }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Penilaian Dosen Asesor</a>
            <a href="{{ route('asset-keuangan') }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Asset Keuangan</a>
        </div>
    </div>

    <a href="{{ route('list-assignment-letter') }}" class="{{ $base }} {{ request()->routeIs('list-assignment-letter') ? $active : $inactive }}">
        <x-admin.icon name="document" class="h-5 w-5" />
        <span>Surat Tugas</span>
        @if($assignmentLetterCount > 0)
            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">{{ $assignmentLetterCount }}</span>
        @endif
    </a>
@endrole

@role('admin')
    <div x-data="{ open: false }" class="{{ $groupWrap }}">
        <button type="button" @click="open = !open" class="{{ $base }} w-full justify-between {{ request()->routeIs('finances.index') || request()->routeIs('assessor-fee.index') || request()->routeIs('rekening.index') ? $active : $inactive }}">
            <span class="flex items-center gap-2"><x-admin.icon name="wallet" class="h-5 w-5" /><span>Keuangan</span></span>
            <x-admin.icon name="chevron-down" class="h-4 w-4 transition-transform" x-bind:class="{ 'rotate-180': open }" />
        </button>
        <div x-show="open" x-cloak x-transition class="{{ $panelClass }}">
            <a href="{{ route('finances.index', ['category' => 'dosen']) }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Laporan Pembayaran</a>
            <a href="{{ route('finances.index', ['category' => 'assessor']) }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Keuangan Assesor</a>
            <a href="{{ route('assessor-fee.index') }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Bea Assesor</a>
            <a href="{{ route('rekening.index') }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Rekening</a>
        </div>
    </div>

    <div x-data="{ open: false }" class="{{ $groupWrap }}">
        <button type="button" @click="open = !open" class="{{ $base }} w-full justify-between {{ request()->routeIs('data-user') || request()->routeIs('assignment-letters.index') ? $active : $inactive }}">
            <span class="flex items-center gap-2"><x-admin.icon name="database" class="h-5 w-5" /><span>Master Data</span></span>
            <x-admin.icon name="chevron-down" class="h-4 w-4 transition-transform" x-bind:class="{ 'rotate-180': open }" />
        </button>
        <div x-show="open" x-cloak x-transition class="{{ $panelClass }}">
            <a href="{{ route('data-user', ['category' => 'dosen']) }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Data Dosen</a>
            <a href="{{ route('data-user', ['category' => 'asesor']) }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Data Assesor</a>
            <a href="{{ route('assignment-letters.index') }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Surat Tugas</a>
        </div>
    </div>

    <div x-data="{ open: false }" class="{{ $groupWrap }}">
        <button type="button" @click="open = !open" class="{{ $base }} w-full justify-between {{ request()->routeIs('users.index') || request()->routeIs('settings.index') || request()->routeIs('ads.index') ? $active : $inactive }}">
            <span class="flex items-center gap-2"><x-admin.icon name="cog" class="h-5 w-5" /><span>Admin</span></span>
            <x-admin.icon name="chevron-down" class="h-4 w-4 transition-transform" x-bind:class="{ 'rotate-180': open }" />
        </button>
        <div x-show="open" x-cloak x-transition class="{{ $panelClass }}">
            <a href="{{ route('users.index') }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Users</a>
            <a href="{{ route('settings.index') }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Settings</a>
            <a href="{{ route('ads.index') }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Ads</a>
        </div>
    </div>
@endrole

@role('operator')
    <div x-data="{ open: false }" class="{{ $groupWrap }}">
        <button type="button" @click="open = !open" class="{{ $base }} w-full justify-between {{ request()->routeIs('assignment-letters.index') ? $active : $inactive }}">
            <span class="flex items-center gap-2"><x-admin.icon name="database" class="h-5 w-5" /><span>Data</span></span>
            <x-admin.icon name="chevron-down" class="h-4 w-4 transition-transform" x-bind:class="{ 'rotate-180': open }" />
        </button>
        <div x-show="open" x-cloak x-transition class="{{ $panelClass }}">
            <a href="{{ route('assignment-letters.index') }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Surat Tugas</a>
        </div>
    </div>
@endrole

<a href="/manual_book" class="{{ $base }} {{ request()->is('manual_book') ? $active : $inactive }}">
    <x-admin.icon name="book" class="h-5 w-5" />
    <span>Buku Panduan</span>
</a>
